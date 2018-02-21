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

namespace app\modules\setup\helpers;

use Yii;
use yii\helpers\VarDumper;
use app\components\console\Console;
use app\models\Role;
use Exception;

class SetupHelper
{

    /**
     * @var string
     */
    public static $purchaseCode = 'aHR0cDovL2Vhc3lmb3Jtcy5iYWx1YXJ0LmNvbS9hY3RpdmF0ZS8=';

    /**
     * Verify access to database config file
     *
     * @return bool
     */
    public static function checkDatabaseConfigFilePermissions()
    {
        $file = Yii::getAlias('@app/config/db.php');

        $result = touch($file); // Check access file

        return $result;
    }

    /**
     * Create database configuration content
     * that will be saved in a file
     *
     * @param $config
     * @return array
     */
    public static function createDatabaseConfig($config)
    {
        $config['class'] = 'yii\db\Connection';
        $config['dsn'] = 'mysql:host='.$config['db_host'].';port='.$config['db_port'].';dbname='.$config['db_name'];
        $config['username'] = $config['db_user'];
        $config['password'] = $config['db_pass'];
        unset(
            $config['db_name'],
            $config['db_host'],
            $config['db_port'],
            $config['db_user'],
            $config['db_pass'],
            $config['connectionOk']
        );
        return $config;
    }

    /**
     * Write database configuration content in a file
     *
     * @param $config
     * @return bool
     */
    public static function createDatabaseConfigFile($config)
    {
        $content = VarDumper::export($config);
        $content = preg_replace('~\\\\+~', '\\', $content); // Fix class backslash
        $content = "<?php\nreturn " . $content . ";\n";

        return file_put_contents(Yii::getAlias('@app/config/db.php'), $content) > 0;
    }

    /**
     * @return array
     */
    public static function executeSqlCommands()
    {
        $sqlFile = Yii::getAlias('@app/easy_forms.sql');

        // Check if SQL file exists and is readable
        if (is_readable($sqlFile)) {
            try {
                // Performing Transactions
                Yii::$app->db->transaction(function () use ($sqlFile) {

                    // Access to DB configuration
                    $db = Yii::$app->db;

                    // Get SQL
                    $sql = file_get_contents($sqlFile);

                    // Performance queries
                    $db->createCommand($sql)->execute();

                });

                // Check if database was successfully installed
                $numberOfRoles = (int) Role::find()->count();

                if ($numberOfRoles > 2) {
                    // Remove the SQL File
                    @unlink($sqlFile);
                    return ['success' => 1, 'message' => 'SQL commands successfully executed.'];
                } else {
                    return ['success' => 0, 'message' => 'We can\'t execute the SQL commands'];
                }

            } catch (Exception $e) {
                return ['success' => 0, 'message' => $e->getMessage()];
            }
        }

        return ['success' => 0, 'message' => 'We can\'t execute the SQL commands'];

    }

    /**
     * Runs migrations
     *
     * @param int $numberOfMigrations
     * @return int
     */
    public static function runMigrations($numberOfMigrations = null)
    {
        try {
            $migrationPath = Yii::getAlias('@app/migrations');

            if (is_dir($migrationPath)) {

                if (!Console::isWindows() && Console::validPhpCliVersion()) {
                    // Run DB Migration on Background
                    $result = Console::run("migrate/up $numberOfMigrations --interactive=0");
                } else {
                    // Run DB Migration
                    $result = Console::runAction('migrate/up', [
                        $numberOfMigrations,
                        'interactive' => false, // Force migrate db without confirmation
                    ]);
                }
                // Verify response
                $lines = is_array($result) ? $result : explode('>', $result);
                $numberOfLines = count($lines);
                if ($numberOfLines > 1) {
                    if (strpos($lines[$numberOfLines-1], 'done') !== false) {
                        return ['success' => 1, 'message' => 'Migrated up successfully.'];
                    }
                }
                // Replace empty message
                if (empty($result)) {
                    return ['success' => 1, 'message' => 'No new migration found. Your system is up-to-date.'];
                }
                // Return complete message
                return ['success' => 0, 'message' => $result];
            }

            return ['success' => 0, 'message' => 'No such migrations directory'];

        } catch (Exception $e) {
            return ['success' => 0, 'message' => $e->getMessage()];
        }
    }
}
