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
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use app\components\behaviors\SluggableBehavior;
use yii\web\NotFoundHttpException;
use yii\helpers\FileHelper;
use yii\web\Response;
use app\components\validators\RecaptchaValidator;
use app\events\SubmissionEvent;
use app\helpers\Language;
use app\helpers\TimeHelper;

/**
 * This is the model class for table "form".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $status
 * @property integer $use_password
 * @property string $password
 * @property integer $authorized_urls
 * @property string $urls
 * @property integer $schedule
 * @property integer $schedule_start_date
 * @property integer $schedule_end_date
 * @property integer $total_limit
 * @property integer $total_limit_number
 * @property string $total_limit_period
 * @property integer $ip_limit
 * @property integer $ip_limit_number
 * @property string $ip_limit_period
 * @property integer $save
 * @property integer $resume
 * @property integer $autocomplete
 * @property integer $novalidate
 * @property integer $analytics
 * @property integer $honeypot
 * @property integer $recaptcha
 * @property string $language
 * @property string $message
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $languageLabel
 *
 * @property User $author
 * @property User $lastEditor
 * @property Theme $theme
 * @property FormData $formData
 * @property FormUI $ui
 * @property FormRule $formRules
 * @property FormConfirmation $formConfirmation
 * @property FormEmail $formEmail
 * @property FormSubmission[] $formSubmissions
 * @property FormSubmissionFile[] $formSubmissionFiles
 * @property FormChart[] $formCharts
 * @property FormUser[] $formUsers
 * @property User[] $users
 */
class Form extends ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const OFF = 0;
    const ON = 1;

    const SAVE_DISABLE = 0;
    const SAVE_ENABLE = 1;

    const RESUME_DISABLE = 0;
    const RESUME_ENABLE = 1;

    const AUTOCOMPLETE_DISABLE = 0;
    const AUTOCOMPLETE_ENABLE = 1;

    const ANALYTICS_DISABLE = 0;
    const ANALYTICS_ENABLE = 1;

    const HONEYPOT_INACTIVE = 0;
    const HONEYPOT_ACTIVE = 1;

    const RECAPTCHA_INACTIVE = 0;
    const RECAPTCHA_ACTIVE = 1;

    const FILES_DIRECTORY = "static_files/uploads"; // Give 0777 permission

    const EVENT_CHECKING_HONEYPOT = "app.form.submission.checkingHoneypot";
    const EVENT_CHECKING_SAVE = "app.form.submission.checkingSave";
    const EVENT_SPAM_DETECTED = "app.form.submission.spamDetected";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form}}';
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
            BlameableBehavior::className(),
            TimestampBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'ensureUnique' => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['schedule_start_date', 'schedule_end_date'], 'required', 'when' => function ($model) {
                return $model->schedule == $this::ON;
            }, 'whenClient' => "function (attribute, value) {
                return $(\"input[name='Form[schedule]']:checked\").val() == '".$this::ON."';
            }"],
            [['password'], 'required', 'when' => function ($model) {
                return $model->use_password == $this::ON;
            }, 'whenClient' => "function (attribute, value) {
                return $(\"input[name='Form[use_password]']:checked\").val() == '".$this::ON."';
            }"],
            [['urls'], 'required', 'when' => function ($model) {
                return $model->authorized_urls == $this::ON;
            }, 'whenClient' => "function (attribute, value) {
                return $(\"input[name='Form[authorized_urls]']:checked\").val() == '".$this::ON."';
            }"],
            [['total_limit_number', 'total_limit_period'], 'required', 'when' => function ($model) {
                return $model->total_limit == $this::ON;
            }, 'whenClient' => "function (attribute, value) {
                return $(\"input[name='Form[total_limit]']:checked\").val() == '".$this::ON."';
            }"],
            [['ip_limit_number', 'ip_limit_period'], 'required', 'when' => function ($model) {
                return $model->ip_limit == $this::ON;
            }, 'whenClient' => "function (attribute, value) {
                return $(\"input[name='Form[ip_limit]']:checked\").val() == '".$this::ON."';
            }"],
            [['message'], 'string'],
            [['status', 'use_password', 'authorized_urls', 'schedule', 'schedule_start_date', 'schedule_end_date',
                'total_limit', 'total_limit_number', 'ip_limit', 'ip_limit_number',
                'save', 'resume', 'autocomplete', 'novalidate', 'analytics', 'honeypot', 'recaptcha',
                'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['total_limit_period', 'ip_limit_period'], 'string', 'max' => 1],
            [['name', 'password'], 'string', 'max' => 255],
            [['urls'], 'string', 'max' => 2555],
            [['password'], 'string', 'min' => 3],
            [['password'], 'filter', 'filter' => 'trim'],
            // ensure empty values are stored as NULL in the database
            ['password', 'default', 'value' => null],
            ['schedule_start_date', 'default', 'value' => null],
            ['schedule_end_date', 'default', 'value' => null],
            [['language'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Form Name'),
            'status' => Yii::t('app', 'Status'),
            'use_password' => Yii::t('app', 'Use password'),
            'password' => Yii::t('app', 'Password'),
            'authorized_urls' => Yii::t('app', 'Authorized URLs'),
            'urls' => Yii::t('app', 'URLs'),
            'schedule' => Yii::t('app', 'Schedule Form Activity'),
            'schedule_start_date' => Yii::t('app', 'Start Date'),
            'schedule_end_date' => Yii::t('app', 'End Date'),
            'total_limit' => Yii::t('app', 'Limit total number of submission'),
            'total_limit_number' => Yii::t('app', 'Total Number'),
            'total_limit_period' => Yii::t('app', 'Per Time Period'),
            'ip_limit' => Yii::t('app', 'Limit submissions from the same IP'),
            'ip_limit_number' => Yii::t('app', 'Max Number'),
            'ip_limit_period' => Yii::t('app', 'Per Time Period'),
            'save' => Yii::t('app', 'Save to DB'),
            'resume' => Yii::t('app', 'Save & Resume Later'),
            'autocomplete' => Yii::t('app', 'Auto complete'),
            'novalidate' => Yii::t('app', 'No validate'),
            'analytics' => Yii::t('app', 'Analytics'),
            'honeypot' => Yii::t('app', 'Spam filter'),
            'recaptcha' => Yii::t('app', 'reCaptcha'),
            'language' => Yii::t('app', 'Language'),
            'message' => Yii::t('app', 'Message'),
            'created_by' => Yii::t('app', 'Created by'),
            'updated_by' => Yii::t('app', 'Updated by'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getFormData()
    {
        return $this->hasOne(FormData::className(), ['form_id' => 'id'])->inverseOf('form');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUi()
    {
        return $this->hasOne(FormUI::className(), ['form_id' => 'id'])->inverseOf('form');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTheme()
    {
        return $this->hasOne(Theme::className(), ['id' => 'theme_id'])
            ->via('ui');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormRules()
    {
        return $this->hasMany(FormRule::className(), ['form_id' => 'id'])->inverseOf('form');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveRules()
    {
        return $this->hasMany(FormRule::className(), ['form_id' => 'id'])
            ->where('status = :status', [':status' => FormRule::STATUS_ACTIVE])
            ->orderBy(['ordinal' => 'ASC', 'id' => 'ASC']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormConfirmation()
    {
        return $this->hasOne(FormConfirmation::className(), ['form_id' => 'id'])->inverseOf('form');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormEmail()
    {
        return $this->hasOne(FormEmail::className(), ['form_id' => 'id'])->inverseOf('form');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormSubmissions()
    {
        return $this->hasMany(FormSubmission::className(), ['form_id' => 'id'])->inverseOf('form');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormSubmissionFiles()
    {
        return $this->hasMany(FormSubmissionFile::className(), ['form_id' => 'id'])->inverseOf('form');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormCharts()
    {
        return $this->hasMany(FormChart::className(), ['form_id' => 'id'])->inverseOf('form');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormUsers()
    {
        return $this->hasMany(FormUser::className(), ['form_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->via('formUsers');
    }

    /**
     * Show label instead of value for boolean Status property
     * @return string
     */
    public function getStatusLabel()
    {
        return $this->status ? Yii::t('app', 'Active') : Yii::t('app', 'Inactive');
    }

    /**
     * Return list of Time Periods
     * @return array
     */
    public function getTimePeriods()
    {
        return TimeHelper::timePeriods();
    }

    /**
     * Returns the language name by its code
     * @return mixed
     */
    public function getLanguageLabel()
    {
        return Language::getLangByCode($this->language);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            // Create files directory, if it doesn't exists
            $filesDirectory = $this::FILES_DIRECTORY . '/' . $this->id;
            if (!is_dir($filesDirectory)) {
                FileHelper::createDirectory($filesDirectory, 0777, true);
            }
        }

    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            // Delete related Models
            $this->formData->delete();
            $this->ui->delete();
            $this->formConfirmation->delete();
            $this->formEmail->delete();

            // Delete all Charts, Submissions and Files related to this form
            // We use deleteAll for performance reason
            FormUser::deleteAll(["form_id" => $this->id]);
            FormRule::deleteAll(["form_id" => $this->id]);
            FormChart::deleteAll(["form_id" => $this->id]);
            FormSubmissionFile::deleteAll(["form_id" => $this->id]);
            FormSubmission::deleteAll(["form_id" => $this->id]);

            // Delete all Stats related to this form
            Event::deleteAll(["app_id" => $this->id]);
            StatsPerformance::deleteAll(["app_id" => $this->id]);
            StatsSubmissions::deleteAll(["app_id" => $this->id]);

            // Removes files directory (and all its content)
            // of this form (if exists)
            FileHelper::removeDirectory($this::FILES_DIRECTORY . '/' . $this->id);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete related stats
     *
     * @return integer The number of rows deleted
     */
    public function deleteStats()
    {
        // Delete all Stats related to this form
        $events = Event::deleteAll(["app_id" => $this->id]);
        $stats = StatsPerformance::deleteAll(["app_id" => $this->id]);
        $submissions = StatsSubmissions::deleteAll(["app_id" => $this->id]);

        return $events + $stats + $submissions;
    }

    /**
     * Check if submission pass Honey Pot trap
     * If no pass throw NotFoundHttpException
     *
     * @param $post
     * @throws NotFoundHttpException
     */
    public function checkHoneypot($post)
    {

        Yii::$app->trigger($this::EVENT_CHECKING_HONEYPOT, new SubmissionEvent([
            'sender' => $this,
        ]));

        if ($this->honeypot === $this::HONEYPOT_ACTIVE) {
            if (isset($post['_email']) && !empty($post['_email'])) {

                Yii::$app->trigger($this::EVENT_SPAM_DETECTED, new SubmissionEvent([
                    'sender' => $this,
                ]));

                throw new NotFoundHttpException();
            }
        }

    }

    public function checkAuthorizedUrls()
    {
        if ($this->authorized_urls === $this::ON) {
            $urls = explode(',', str_replace(' ', '', $this->urls));
            if(isset($_SERVER['HTTP_REFERER'])) {
                $ar = parse_url($_SERVER['HTTP_REFERER']);
                if (!in_array($ar['host'], $urls)) {
                    throw new NotFoundHttpException();
                }
            }
        }
    }

    /**
     * reCaptcha Validation
     *
     * @param $post
     */
    public function validateRecaptcha($post)
    {

        // Only if Form has reCaptcha component and was not passed in this session
        if ($this->recaptcha === $this::RECAPTCHA_ACTIVE && !Yii::$app->session['reCaptcha']) {

            $recaptchaValidator = new RecaptchaValidator();

            if (!$recaptchaValidator->validate($post[$recaptchaValidator::CAPTCHA_RESPONSE_FIELD], $message)) {

                $errors = [
                    'field' => $this->formData->getRecaptchaFieldID(),
                    'messages' => [$message],
                ];

                /** @var \yii\web\Response $response */
                $response = Yii::$app->getResponse();
                $response->format = Response::FORMAT_JSON;
                $response->data = array(
                    'action'  => 'submit',
                    'success' => false,
                    'id' => 0,
                    'message' => Yii::t('app', 'There is {startTag}an error in your submission{endTag}.', [
                        'startTag' => '<strong>',
                        'endTag' => '</strong>',
                    ]),
                    'errors' => [$errors],
                );
                $response->send();

                exit;
            }
        }
    }

    /**
     * Check if form does no accept more submissions
     */
    public function checkTotalLimit()
    {
        if ($this->total_limit === $this::ON) {

            $startTime = TimeHelper::startTime($this->total_limit_period);

            $submissions = FormSubmission::find()->select('id')->asArray()
                ->where(['form_id' => $this->id])
                ->andWhere(['between','created_at', $startTime, time()])->count();

            if ($this->total_limit_number <= $submissions) {
                /** @var \yii\web\Response $response */
                $response = Yii::$app->getResponse();
                $response->format = Response::FORMAT_JSON;
                $response->data = array(
                    'action'  => 'submit',
                    'success' => false,
                    'id' => 0,
                    'message' => Yii::t("app", "Sorry, the form does not accept more submissions per {period}.", [
                        'period' => TimeHelper::getPeriodByCode($this->total_limit_period)]),
                );
                $response->send();

                exit;
            }
        }
    }

    /**
     * Check if user has reached his submission limit
     */
    public function checkIPLimit()
    {
        if ($this->ip_limit === $this::ON) {

            $startTime = TimeHelper::startTime($this->ip_limit_period);

            $ip = Yii::$app->getRequest()->getUserIP();

            if ($ip === "::1") {
                // Usefull when app run in localhost
                $ip = "81.2.69.160";
            }

            $submissions = FormSubmission::find()->select('id')->asArray()
                ->where(['form_id' => $this->id])
                ->andWhere(['between','created_at', $startTime, time()])
                ->andWhere(['ip' => $ip])
                ->count();

            if ($this->ip_limit_number <= $submissions) {
                /** @var \yii\web\Response $response */
                $response = Yii::$app->getResponse();
                $response->format = Response::FORMAT_JSON;
                $response->data = array(
                    'action'  => 'submit',
                    'success' => false,
                    'id' => 0,
                    'message' => Yii::t("app", "You have reached your Submission Limit per {period}.", [
                        'period' => TimeHelper::getPeriodByCode($this->ip_limit_period)]),
                );
                $response->send();

                exit;
            }
        }
    }

    public function saveToDB()
    {

        Yii::$app->trigger($this::EVENT_CHECKING_SAVE, new SubmissionEvent([
            'sender' => $this,
        ]));

        return ($this->save === $this::SAVE_ENABLE);
    }
}
