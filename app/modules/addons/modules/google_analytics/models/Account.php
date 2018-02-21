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

namespace app\modules\addons\modules\google_analytics\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\Form;

/**
 * This is the model class for table "addon_google_analytics".
 *
 * @property integer $id
 * @property integer $form_id
 * @property string $tracking_id
 * @property string $tracking_domain
 * @property integer $status
 * @property integer $anonymize_ip
 *
 * @property \app\models\Form $form
 */
class Account extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%addon_google_analytics}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id', 'status', 'anonymize_ip'], 'integer'],
            [['form_id', 'tracking_id', 'tracking_domain'], 'required'],
            [['tracking_id', 'tracking_domain'], 'string', 'max' => 255],
            ['tracking_id', 'match', 'pattern' => '/(UA|YT|MO)-\d+-\d+/i'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('google_analytics', 'ID'),
            'form_id' => Yii::t('google_analytics', 'Form'),
            'status' => Yii::t('google_analytics', 'Status'),
            'tracking_id' => Yii::t('google_analytics', 'Tracking ID'),
            'tracking_domain' => Yii::t('google_analytics', 'Tracking Domain'),
            'anonymize_ip' => Yii::t('google_analytics', 'Anonymize Ip'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::className(), ['id' => 'form_id']);
    }
}
