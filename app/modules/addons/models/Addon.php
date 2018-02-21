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

namespace app\modules\addons\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "addon".
 *
 * @property integer $id
 * @property string $class
 * @property string $name
 * @property string $description
 * @property string $version
 * @property integer $status
 * @property integer $installed
 *
 */
class Addon extends ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const INSTALLED_OFF = 0;
    const INSTALLED_ON = 1;

    const CACHE_KEY = 'addon';

    public static function tableName()
    {
        return '{{%addon}}';
    }

    public function rules()
    {
        return [
            [['id', 'class', 'name'], 'required'],
            [['id', 'class', 'name'], 'trim'],
//            ['id',  'match', 'pattern' => '/^[a-z]+$/'],
            ['id',  'match', 'pattern' => '/^[a-zA-Z0-9-_\-]+$/'],
            ['id', 'unique'],
            ['class',  'match', 'pattern' => '/^[\w\\\]+$/'],
            ['class',  'classExists'],
            [['status','installed'], 'in', 'range' => [0,1]],
            [['status','installed'], 'default', 'value' => 0],
            [['id', 'class', 'name', 'description', 'version'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('addon', 'ID'),
            'class' => Yii::t('addon', 'Class'),
            'name' => Yii::t('addon', 'Name'),
            'description' => Yii::t('addon', 'Description'),
            'version' => Yii::t('addon', 'Version'),
            'status' => Yii::t('addon', 'Status'),
            'installed' => Yii::t('addon', 'Installed'),
        ];
    }

    public static function find()
    {
        return new AddonQuery(get_called_class());
    }

    public function classExists($attribute)
    {
        if (!class_exists($this->$attribute)) {
            $this->addError($attribute, Yii::t('addon', 'Class does not exist'));
        }
    }

    public function verifyNews()
    {
    }
}
