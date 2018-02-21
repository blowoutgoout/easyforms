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
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use Carbon\Carbon;
use app\components\validators\DataValidator;
use app\components\analytics\enricher\IpLookupsEnrichment;
use app\helpers\Html;
use yii\helpers\Json;

/**
 * This is the model class for table "form_submissions".
 *
 * @property integer $id
 * @property integer $form_id
 * @property integer $status
 * @property integer $new
 * @property integer $important
 * @property string $sender
 * @property string $data
 * @property string $ip
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $submitted
 * @property string $updated
 *
 * @property Form $form
 * @property User $author
 * @property User $lastEditor
 * @property FormSubmissionFile[] $files
 */
class FormSubmission extends ActiveRecord
{

    private $idCache;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form_submission}}';
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
    public function init()
    {
        Carbon::setLocale(substr(Yii::$app->language, 0, 2)); // eg. en-US to en
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            BlameableBehavior::className(),
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return ['id', 'form_id', 'status', 'new', 'important',
            'sender', 'data', 'files', 'comments', 'authorName', 'lastEditorName', 'ip',
            'created_at', 'updated_at', 'submitted', 'updated'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id'], 'required'],
            [['form_id', 'status', 'new', 'important','created_by', 'updated_by',
                'created_at', 'updated_at'], 'integer'],
            [['data'], 'requiredFieldsValidation', 'skipOnEmpty' => false, 'skipOnError' => false, 'on' => ['public']],
            [['data'], 'uniqueFieldsValidation', 'skipOnEmpty' => false, 'skipOnError' => false,
                'on' => ['public', 'administration']],
            [['data'], 'fieldTypeValidation', 'skipOnEmpty' => false, 'skipOnError' => false,
                'on' => ['public', 'administration']],
            [['sender', 'ip'], 'safe']
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
            'status' => Yii::t('app', 'Status'),
            'new' => Yii::t('app', 'New'),
            'important' => Yii::t('app', 'Important'),
            'sender' => Yii::t('app', 'Sender'),
            'data' => Yii::t('app', 'Data'),
            'ip' => Yii::t('app', 'IP Address'),
            'created_by' => Yii::t('app', 'Created by'),
            'updated_by' => Yii::t('app', 'Updated by'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastEditor()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return null|string Name of the author
     */
    public function getAuthorName()
    {
        if (isset($this->author) && isset($this->author->username)) {
            return $this->author->username;
        }

        return null;
    }

    /**
     * @return null|string Name of the last editor
     */
    public function getLastEditorName()
    {
        if (isset($this->lastEditor) && isset($this->lastEditor->username)) {
            return $this->lastEditor->username;
        }

        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(FormSubmissionFile::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(FormSubmissionComment::className(), ['submission_id' => 'id']);
    }

    /**
     * Created at with format
     *
     * @return string
     */
    public function getSubmitted()
    {
        return Carbon::createFromTimestampUTC($this->created_at)->diffForHumans();
    }

    /**
     * Updated at with format
     *
     * @return string
     */
    public function getUpdated()
    {
        return Carbon::createFromTimestampUTC($this->updated_at)->diffForHumans();
    }


    public function requiredFieldsValidation()
    {
        $dataValidator = new DataValidator($this);
        $dataValidator->requiredFieldsValidation();
        if ($dataValidator->hasErrors()) {
            $this->addErrors($dataValidator->getErrors());
        }
    }

    public function fieldTypeValidation()
    {
        $dataValidator = new DataValidator($this);
        $dataValidator->fieldTypeValidation();
        if ($dataValidator->hasErrors()) {
            $this->addErrors($dataValidator->getErrors());
        }
    }

    public function uniqueFieldsValidation()
    {
        $dataValidator = new DataValidator($this);
        $dataValidator->uniqueFieldsValidation();
        if ($dataValidator->hasErrors()) {
            $this->addErrors($dataValidator->getErrors());
        }
    }

    /**
     * Clean Submission of null fields
     *
     * Remove keys with NULL, but leave values of FALSE, Empty Strings ("") and 0 (zero)
     *
     * @param $fields
     * @param $post
     * @return array
     */
    public function cleanSubmission($fields, $post)
    {
        $submission = [];
        foreach ($fields as $field) {
            $submission[$field["name"]] = isset($post[$field["name"]]) ? $post[$field["name"]] : null;
        }

        // Remove keys with NULL, but leave values of FALSE, Empty Strings ("") and 0 (zero)
        $submission = array_filter($submission, function ($val) {
            return $val !== null;
        });

        // Strip whitespace from the beginning and end of each string element of the array
        $submission = array_map(function ($el) {
            if (is_string($el)) {
                return trim($el);
            } elseif (is_array($el)) {
                // For Select List & Checkbox elements
                array_map('trim', $el);
                return $el;
            }
            return $el;
        }, $submission);

        return $submission;
    }

    /**
     * Return array of UploadedFile
     *
     * @param $fileFields
     * @return array
     */
    public function getUploadedFiles($fileFields)
    {
        // Array of files
        $files = [];

        // Note: Load file here, to prevent save incomplete data
        // For example, by memory exhaust error

        // If form has file fields
        foreach ($fileFields as $name => $label) {
            // Get the file
            $uploadedFile = UploadedFile::getInstanceByName($name);
            $file = [
                'name' => $name,
                'label' => $label,
                'file' => $uploadedFile,
            ];
            // Add to array
            array_push($files, $file);
        }

        return $files;

    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        // Decode as json json assoc array
        $this->data = json_decode($this->data, true);

        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($insert) {
                // Sender location
                $this->ip = $this->getUserIP();
                $ipEnriched = new IpLookupsEnrichment($this->ip);
                $location = $ipEnriched->getData();

                // Sender
                $sender = array(
                    'country' => isset($location["geo_country_name"]) ? $location["geo_country_name"] : '',
                    'city' => isset($location["geo_city"]) ? $location["geo_city"] : '',
                    'latitude' => isset($location["geo_latitude"]) ? $location["geo_latitude"] : '',
                    'longitude' => isset($location["geo_longitude"]) ? $location["geo_longitude"] : '',
                    'user_agent' => Yii::$app->request->getUserAgent(),
                );

                // Encode sender data as json object
                $this->sender = json_encode($sender, JSON_FORCE_OBJECT);
            }

            // Encode submission data
            $this->data = Json::encode($this->data); // Encode as json assoc array and UTF-8

            return true;

        } else {

            return false;

        }
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $this->idCache = $this->id;
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        // Delete Files
        foreach (FormSubmissionFile::find()->where(['submission_id' => $this->idCache])->all() as $fileModel) {
            $fileModel->delete();
        }

        // Delete Comments
        foreach (FormSubmissionComment::find()->where(['submission_id' => $this->idCache])->all() as $commentModel) {
            $commentModel->delete();
        }
    }

    /**
     * Get User IP
     * @return string
     */
    private function getUserIP()
    {

        // $ip = Yii::$app->getRequest()->getUserIP();
        $ip = getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                    getenv('HTTP_FORWARDED_FOR')?:
                        getenv('HTTP_FORWARDED')?:
                            getenv('REMOTE_ADDR');

        if ($ip === "::1") {
            // Useful when app run in localhost
            $ip = "81.2.69.160";
        }

        return $ip;
    }

    /**
     * Get Referrer Information
     * To detect it, Analytics must be enabled in Form Settings.
     *
     * @return array
     */
    private function getReferrer()
    {
        $referrer = [];
        $referrerLink = Yii::$app->request->getReferrer();
        if (!empty($referrerLink)) {
            parse_str($referrerLink, $link);
            if (isset($link['url']) || isset($link['referrer'])) {
                if (isset($link['url']) && !empty($link['url'])) {
                    $referrer['url'] = $link['url'];
                }
                if (isset($link['referrer']) && !empty($link['referrer'])) {
                    $referrer['referrer'] = $link['referrer'];
                }
                return $referrer;
            }
        }
        return [];
    }

    /**
     * Parse Submission data
     *
     * @return array|mixed|string
     * @throws \yii\base\InvalidConfigException
     */
    public function getSubmissionData()
    {
        $data = [];
        $sender = [];
        $referrer = $this->getReferrer();

        if (!empty($this->data)) {
            if (is_string($this->data)) {
                $data = json_decode($this->data, true);
            } else {
                $data = $this->data;
            }
        }

        if (!empty($this->sender)) {
            if (is_string($this->sender)) {
                $sender = json_decode($this->sender, true);
            } else {
                $sender = $this->sender;
            }
        }

        return $data + [
            'form_id' => $this->form->id,
            'submission_id' => $this->id,
            'form_name' => isset($this->form->name) ? Html::encode($this->form->name) : '',
            'ip_address' => $this->ip,
            'created_at' => Yii::$app->formatter->asDatetime($this->created_at),
            'updated_at' => Yii::$app->formatter->asDatetime($this->updated_at),
        ] + $sender + $referrer;
    }
}
