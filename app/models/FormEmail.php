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

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "form_email".
 *
 * @property integer $id
 * @property integer $form_id
 * @property string $to
 * @property string $from
 * @property string $cc
 * @property string $bcc
 * @property string $subject
 * @property integer $type
 * @property string $message
 * @property integer $plain_text
 * @property integer $attach
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $typeLabel
 *
 * @property Form $form
 */
class FormEmail extends ActiveRecord
{

    const TYPE_ALL = 0;
    const TYPE_LINK = 1;
    const TYPE_MESSAGE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form_email}}';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id'], 'required'],
            [['form_id', 'type', 'plain_text', 'attach', 'created_at', 'updated_at'], 'integer'],
            [['message'], 'string'],
            [['to', 'cc', 'bcc'], 'trim'],
            [['to', 'cc', 'bcc'], 'email'],
            [['to', 'from', 'cc', 'bcc', 'subject'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'form_id' => Yii::t('app', 'Form ID'),
            'to' => Yii::t('app', 'Recipient'),
            'from' => Yii::t('app', 'Reply To'),
            'cc' => Yii::t('app', 'Carbon Copy (cc)'),
            'bcc' => Yii::t('app', 'Blind Carbon Copy (bcc)'),
            'subject' => Yii::t('app', 'Subject'),
            'type' => Yii::t('app', 'Contents'),
            'message' => Yii::t('app', 'Message'),
            'plain_text' => Yii::t('app', 'Only Plain Text'),
            'attach' => Yii::t('app', 'Attach'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::className(), ['id' => 'form_id']);
    }

    /**
     * @return bool
     */
    public function fromIsEmail()
    {
        $email = $this->from;
        // Remove all illegal characters from email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        // Validate e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return true;
        }
        return false;
    }

    /**
     * Show label instead of value for integer Type property
     * @return string
     */
    public function getTypeLabel()
    {
        $label = "";
        switch ($this->type) {
            case $this::TYPE_ALL:
                $label = Yii::t('app', 'All Data');
                break;
            case $this::TYPE_LINK:
                $label = Yii::t('app', 'Only Link');
                break;
            case $this::TYPE_MESSAGE:
                $label = Yii::t('app', 'Custom Message');
                break;
        };
        return $label;
    }

    /**
     * Return email views according to settings
     *
     * @return array
     */
    public function getEmailViews()
    {
        $content = [
            'html' => 'submission-html',
            'text' => 'submission-text'
        ];

        if ($this->type == $this::TYPE_LINK) {
            $content = [
                'html' => 'link-html',
                'text' => 'link-text',
            ];
        } elseif ($this->type == $this::TYPE_MESSAGE) {
            $content = [
                'html' => 'message-html',
                'text' => 'message-text',
            ];
        }

        if ($this->plain_text) {
            $content['html'] = 'submission-html-as-text';
        }

        return $content;
    }
}
