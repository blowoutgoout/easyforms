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
use yii\helpers\Url;

/**
 * This is the model class for table "form_submission_file".
 *
 * @property integer $id
 * @property integer $submission_id
 * @property integer $form_id
 * @property integer $field
 * @property integer $label
 * @property string $name
 * @property string $extension
 * @property integer $size
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class FormSubmissionFile extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form_submission_file}}';
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
    public function fields()
    {
        return ['id', 'field', 'label', 'name', 'originalName','extension', 'sizeWithUnit', 'link'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['submission_id', 'form_id', 'name', 'size'], 'required'],
            [['field', 'label', 'name', 'extension'], 'string'],
            [['id', 'submission_id', 'form_id', 'size', 'status', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'submission_id' => Yii::t('app', 'Submission ID'),
            'form_id' => Yii::t('app', 'Form ID'),
            'field' => Yii::t('app', 'Field'),
            'label' => Yii::t('app', 'Label'),
            'name' => Yii::t('app', 'Name'),
            'extension' => Yii::t('app', 'Extension'),
            'size' => Yii::t('app', 'Size'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            // Delete file if exists
            $filePath = $this->getFilePath();
            if (file_exists($filePath)) {
                @unlink($filePath); // Don't show errors
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the complete name of the file
     * (name with extension)
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->name . "." . $this->extension;
    }

    /**
     * Returns the file directory
     * (baseDirectory with Form Id)
     *
     * @return string
     */
    public function getFileDirectory()
    {
        return Form::FILES_DIRECTORY . '/' . $this->form_id;
    }

    /**
     * Returns the file path
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->getFileDirectory() . '/' . $this->getFileName();
    }

    /**
     * Returns a relative link for download the file
     *
     * @return string
     */
    public function getLink()
    {
        return Url::base(false) . '/' . $this->getFilePath();
    }

    /**
     * Returns the size of the file with unit
     *
     * @return string
     */
    public function getSizeWithUnit()
    {
        return $this->formatBytes($this->size);
    }

    /**
     * Returns the original filename
     * Removes submission id to filename
     *
     * @return mixed
     */
    public function getOriginalName()
    {
        return preg_replace('/-[^-]*$/', '', $this->name);
    }

    /**
     * Format the size for the given number of bytes
     *
     * @param int $bytes Number of bytes (eg. 25907)
     * @param int $precision [optional] Number of digits after the decimal point (eg. 1)
     * @return string Value converted with unit (eg. 25.3KB)
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
