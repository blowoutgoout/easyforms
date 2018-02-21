<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\components\analytics\modeler;

use Yii;

/**
 * Class MySQLCommands
 * @package app\components\analytics\modeler
 */
class MySQLCommands
{

    public $eventTable = "{{%event}}";

    /**
     * Run multiple related queries in a transaction to ensure
     * the integrity and consistency of the database.
     *
     * @throws \Exception
     */
    public function execute()
    {

        // Performing Transactions
        Yii::$app->db->transaction(function () {

            // Access to DB configuration
            $db = Yii::$app->db;

            // Performance stats
            $db->createCommand($this->dropTmpTables())->execute();
            $db->createCommand($this->sumUsersByDay())->execute();
            $db->createCommand($this->sumFillsByDay())->execute();
            $db->createCommand($this->sumConversionsByDay())->execute();
            $db->createCommand($this->savePerformanceByDay())->execute();
            $db->createCommand($this->dropTmpTables())->execute();

            // Submissions stats
            $db->createCommand($this->deleteCurrentDaySubmissions())->execute();
            $db->createCommand($this->saveNewSubmissions())->execute();

            // Backup events
            $db->createCommand($this->logEvents())->execute();

            // Delete events (Leave ready to insert new data)
            $db->createCommand($this->deleteEvents())->execute();

        });

    }

    /**
     * Create a temporal table for save unique users stats
     *
     * @return string
     */
    protected function sumUsersByDay()
    {
        $sql = "
        CREATE TABLE {{%stats_users_tmp}}
            SELECT
                DATE(FROM_UNIXTIME(collector_tstamp)) as day,
                app_id,
                COUNT(DISTINCT(domain_userid)) AS users
            FROM $this->eventTable
            WHERE event = 'pv'
            GROUP BY 1,2
            ORDER BY 1,2
        ";

        return $sql;
    }

    /**
     * Create a temporal table for save fills event stats
     *
     * @return string
     */
    protected function sumFillsByDay()
    {
        $sql = "
        CREATE TABLE {{%stats_fills_tmp}}
            SELECT
                DATE(FROM_UNIXTIME(collector_tstamp)) as day,
        		app_id,
                COUNT(DISTINCT(domain_userid)) AS fills
            FROM $this->eventTable
            WHERE event = 'se'
            AND se_category = 'form'
            AND se_action = 'fill'
            GROUP BY 1,2
            ORDER BY 1,2
        ";

        return $sql;
    }

    /**
     * Create a temporal table for save conversions event stats
     *
     * @return string
     */
    protected function sumConversionsByDay()
    {
        $sql = "
        CREATE TABLE {{%stats_conversions_tmp}}
            SELECT
                DATE(FROM_UNIXTIME(collector_tstamp)) as day,
        		app_id,
                COUNT(DISTINCT(domain_userid)) AS conversions,
            	SUM(se_value) AS conversionTime
            FROM $this->eventTable
            WHERE event = 'se'
            AND se_category = 'form'
            AND se_action = 'submit'
            GROUP BY 1,2
            ORDER BY 1,2
        ";

        return $sql;
    }

    /**
     * Aggregate performance stats from temporal tables
     *
     * @return string
     */
    protected function savePerformanceByDay()
    {
        $sql = "
        INSERT INTO {{%stats_performance}} (day, app_id, users, fills, conversions, conversionTime)
            SELECT su.day,
            su.app_id,
            su.users,
            sf.fills,
            sc.conversions,
            sc.conversionTime
        FROM {{%stats_users_tmp}} AS su
        LEFT JOIN {{%stats_fills_tmp}} AS sf
            ON su.day = sf.day AND su.app_id = sf.app_id
        LEFT JOIN {{%stats_conversions_tmp}} AS sc
            ON su.day = sc.day AND su.app_id = sc.app_id
        GROUP BY 1,2
        ORDER BY 1,2
        ON DUPLICATE KEY UPDATE
            users=values(users), fills=values(fills),
            conversions=values(conversions), conversionTime=values(conversionTime)
        ";

        return $sql;
    }

    /**
     * Drop temporal tables
     *
     * @return string
     */
    protected function dropTmpTables()
    {
        $sql = "DROP TABLE IF EXISTS {{%stats_users_tmp}}, {{%stats_fills_tmp}}, {{%stats_conversions_tmp}};";

        return $sql;
    }

    /**
     * Return a MySQL statement for delete submit event data of the current day
     *
     * @return string
     */
    protected function deleteCurrentDaySubmissions()
    {
        $sql = "DELETE FROM {{%stats_submissions}} WHERE DATE(FROM_UNIXTIME(collector_tstamp)) > CURRENT_DATE - 1";

        return $sql;
    }

    /**
     * Return a MySQL statement for insert submit event data to the stats_submissions table
     *
     * @return string
     */
    protected function saveNewSubmissions()
    {
        $sql = "
        INSERT INTO {{%stats_submissions}}
            SELECT
                app_id,

                collector_tstamp,

                domain_sessionidx,

                geo_country,
                geo_city,

                refr_urlhost,

                refr_medium,

                br_family,

                os_family,

                dvce_type,
                dvce_ismobile

            FROM $this->eventTable
            WHERE event = 'se'
            AND se_category = 'form'
            AND se_action = 'submit'
        ";

        return $sql;
    }

    /**
     * Return a MySQL statement for insert event data to the log_event table
     *
     * @return string
     */
    protected function logEvents()
    {
        $sql = "
        INSERT INTO {{%log_event}}
            SELECT * FROM $this->eventTable
            WHERE DATE(FROM_UNIXTIME(collector_tstamp)) < CURRENT_DATE
        ";

        return $sql;
    }

    /**
     * Return a MySQL statement for delete event data of the event table
     *
     * @return string
     */
    protected function deleteEvents()
    {
        // Delete all records except those for the current day
        $sql = "DELETE FROM $this->eventTable WHERE DATE(FROM_UNIXTIME(collector_tstamp)) < CURRENT_DATE";

        return $sql;
    }
}
