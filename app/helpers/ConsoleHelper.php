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

namespace app\helpers;

use Yii;
use yii\console\Application;
use yii\web\ServerErrorHttpException;

/**
 * Class ConsoleHelper
 * @package app\helpers
 * @deprecated since 1.3.1, will be removed from 1.4
 */
class ConsoleHelper
{
    /** @var Application $console Console Application */
    private static $console;

    /**
     * Running console command on background and return output
     *
     * @param string $cmd Argument that will be passed to console application
     * @return array [status, output]
     */
    public static function run($cmd)
    {
        $cmd = Yii::getAlias('@app/yii') . ' ' . $cmd;
        $handler = null;
        if (self::isWindows() === true) {
            $handler = popen('start ' . $cmd, 'r');

        } else {
            $handler = popen($cmd, 'r');
        }
        $output = '';
        while (!feof($handler)) {
            $output .= fgets($handler);
        }
        $output = trim($output);
        $status = pclose($handler);
        return [$status, $output];
    }

    /**
     * Running console command on background
     *
     * @param string $cmd Argument that will be passed to console application
     * @return int
     */
    public static function runOnBackground($cmd)
    {
        $cmd = Yii::getAlias('@app/yii') . ' ' . $cmd;
        if (self::isWindows() === true) {
            return pclose(popen('start /b ' . $cmd, 'r'));
        } else {
            return pclose(popen($cmd . ' > /dev/null &', 'r'));
        }
    }

    /**
     * Return console application
     *
     * @return Application
     * @throws ServerErrorHttpException
     */
    public static function console()
    {
        if (!self::$console) {

            $oldApp = Yii::$app;

            $consoleConfigFile = Yii::getAlias('@app/config/console.php');

            if (!file_exists($consoleConfigFile) || !is_array(($consoleConfig = require($consoleConfigFile)))) {
                throw new ServerErrorHttpException('Cannot find `'.
                    Yii::getAlias('@app/config/console.php').'`. Please create and configure console config.');
            }

            self::$console = new Application($consoleConfig);

            Yii::$app = $oldApp;
        }

        return self::$console;
    }

    /**
     * Run up migrations in background
     *
     * @param $migrationPath
     * @param $migrationTable
     */
    public static function migrate($migrationPath, $migrationTable)
    {
        self::runOnBackground('migrate --migrationPath=' . $migrationPath . ' --migrationTable=' . $migrationTable .
        ' --interactive=0');
    }

    /**
     * Run down migrations in background
     *
     * @param $migrationPath
     * @param $migrationTable
     */
    public static function migrateDown($migrationPath, $migrationTable)
    {
        self::runOnBackground('migrate/down --migrationPath=' . $migrationPath . ' --migrationTable=' .
            $migrationTable . ' --interactive=0');
    }

    /**
    * Check if currently running under MS Windows
    *
    * @see http://stackoverflow.com/questions/738823/possible-values-for-php-os
    * @return bool
    */
    public static function isWindows()
    {
        return
            (defined('PHP_OS') && (substr_compare(PHP_OS, 'win', 0, 3, true) === 0)) ||
            (getenv('OS') != false && substr_compare(getenv('OS'), 'windows', 0, 7, true))
            ;
    }
}
