<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.1
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\modules\update\helpers;

use Yii;
use app\components\console\Console;
use Exception;

class SetupHelper
{

    /**
     * Runs new migrations
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
                // Detect no new migrations
                if (strpos($lines[$numberOfLines-1], 'up-to-date') !== false) {
                    return ['success' => 1, 'message' => 'No new migration found. Your system is up-to-date.'];
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
