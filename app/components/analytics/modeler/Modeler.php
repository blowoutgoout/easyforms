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
 * Class Modeler
 * At the end of the data modeling,
 * a clean set of tables are available which make it easier to perform analysis on the data.
 *
 * @package app\components\analytics\modeler
 */
class Modeler
{

    /**
     * Execute SQL statements
     */
    public function run()
    {
        if (Yii::$app->db->driverName === "mysql") {
            $commands = new MySQLCommands();
            $commands->execute();
        }
    }
}
