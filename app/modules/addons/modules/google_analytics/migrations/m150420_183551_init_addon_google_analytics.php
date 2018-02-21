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

use yii\db\Schema;
use yii\db\Migration;

class m150420_183551_init_addon_google_analytics extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%addon_google_analytics}}', [
            'id' => Schema::TYPE_PK,
            'form_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'tracking_id' => Schema::TYPE_STRING . '(255) NOT NULL',
            'tracking_domain' => Schema::TYPE_STRING . '(255) NOT NULL',
            'status' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'anonymize_ip' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%addon_google_analytics}}');
    }
}
