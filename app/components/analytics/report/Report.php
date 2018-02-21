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

namespace app\components\analytics\report;

use Yii;
use SplTempFileObject;
use yii\db\Query;
use yii\base\InvalidParamException;
use League\Csv\Writer;

/**
 * Class Report
 * @package app\components\analytics\report
 */
class Report
{
    /**
     * Id of the app / form
     */
    public $appId;

    /**
     * DB Table
     */
    public $report; // 'performance' or 'submissions'

    /**
     * @var \yii\db\Query
     */
    protected $query;

    /**
     * Set appId
     *
     * @param $appId
     * @return $this
     */
    public function app($appId)
    {
        $this->appId = $appId;
        return $this;
    }

    /**
     * Set report
     *
     * @param $report
     * @return $this
     */
    public function report($report)
    {
        $this->report = $report;
        return $this;
    }

    /**
     * Prepare the DB query
     *
     * @return $this
     */
    public function prepare()
    {
        // Throw a exception if a required parameter is not defined
        $this->verifyRequiredParameters();

        // Build the query
        $this->query = new Query();

        $this->query->select('*');

        $this->query->from($this->getTableName());

        $this->query->where('app_id=:appId', [':appId'=>$this->appId]);

        return $this;
    }

    /**
     * Perform the DB Query
     *
     * @return array
     */
    public function perform()
    {
        // Create the command
        $command = $this->query->createCommand();

        // Execute the command and return
        return $command->queryAll();
    }

    /**
     * Perform a Database Query and show the result as CSV
     *
     * By default, write to the output buffer.
     * But, if you set the filename, the file will be downloadable.
     *
     * @param string $filename Name of the file to download
     */
    public function performAsCSV($filename = null)
    {

        // Create the CSV into memory
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        // Insert fields names as the CSV header
        $csv->insertOne($this->getCSVHeader());

        // Print to the output stream
        foreach ($this->query->batch() as $items) {
            // $items is an array of 100 or fewer rows from the db table
            $csv->insertAll($items);
        }

        $csv->output($filename);
    }

    /**
     * Return the Table name
     *
     * @return null|string
     * @throws \yii\base\InvalidParamException
     */
    protected function getTableName()
    {
        $table = null;

        $table = "{{%stats_" . $this->report . "}}";

        return $table;
    }

    /**
     * Verify required parameters
     */
    protected function verifyRequiredParameters()
    {

        if ($this->appId === null) {

            throw new InvalidParamException(Yii::t("app", "App ID is a required parameter."));

        } elseif ($this->report === null) {

            throw new InvalidParamException(Yii::t("app", "Report is a required parameter."));

        }

    }

    protected function getCSVHeader()
    {
        $fields = array();

        if ($this->report == "performance") {

            $fields = $this->getStatsPerformanceFields();

        } elseif ($this->report == "submissions") {

            $fields = $this->getStatsSubmissionsFields();

        }

        return $fields;
    }

    protected function getStatsPerformanceFields()
    {
        $select = array(
            'day',
            'app_id',
            'users',
            'fills',
            'conversions',
            'conversionTime',
        );

        return $select;
    }

    /**
     * Return the fields of the stats_conversions table to select in a query
     *
     * @return array
     */
    protected function getStatsSubmissionsFields()
    {
        $select = array(
            // App
            'app_id',

            // Date/time
            'collector_tstamp',

            'domain_sessionidx',

            // Location
            'geo_country',
            'geo_city',

            // Referrer URL components
            'refr_urlhost',

            // Referrer details
            'refr_medium',

            // Browser
            'br_family',

            // Operating System
            'os_family',

            // Device/Hardware
            'dvce_type',
            'dvce_ismobile',
        );

        return $select;
    }
}
