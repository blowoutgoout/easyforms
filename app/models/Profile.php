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

/**
 * This is the model class for table "tbl_profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property string  $full_name
 * @property string  $company
 * @property string  $avatar
 * @property string  $timezone
 * @property string  $language
 *
 * @property User $user
 * @property mixed   $image
 */
class Profile extends ActiveRecord
{

    /**
     * @var string
     */
    public $avatarsDir = "static_files/images/avatars";

    /**
     * @var mixed image the attribute for rendering the file input
     * widget for upload on the form
     */
    public $image;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return "{{%profile}}";
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
    public function rules()
    {
        return [
//            [['user_id'], 'required'],
//            [['user_id'], 'integer'],
//            [['create_time', 'update_time'], 'safe'],
            [['full_name', 'company', 'avatar', 'timezone', 'language'], 'string', 'max' => 255],
            [['image'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif', 'maxSize' => 512 * 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'user_id'     => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'full_name'   => Yii::t('app', 'Full Name'),
            'company'   => Yii::t('app', 'Company'),
            'avatar'   => Yii::t('app', 'Avatar'),
            'image'   => Yii::t('app', 'Avatar'),
            'language'   => Yii::t('app', 'Language'),
            'timezone'   => Yii::t('app', 'Timezone'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => function ($event) {
                    return gmdate("Y-m-d H:i:s");
                },
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        /** @var User $user */
        $user = Yii::$app->getModule("user")->model("User");
        return $this->hasOne($user::className(), ['id' => 'user_id']);
    }

    /**
     * Set user id
     *
     * @param int $userId
     * @return static
     */
    public function setUser($userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * Return the avatar full url
     *
     * @return string
     */
    public function getAvatarUrl()
    {
        $avatar = isset($this->avatar) ? $this->avatar : "placeholder.png";
        return Yii::getAlias('@web') . '/' . $this->avatarsDir . '/' . $avatar;
    }

    /**
     * Return stored avatar path
     * @return string
     */
    public function getImageFile()
    {
        // Return a default image placeholder if the avatar is not found
        $avatar = isset($this->avatar) ? $this->avatarsDir . '/' . $this->avatar : null;
        return $avatar;
    }

    /**
     * Upload avatar image
     *
     * @return bool
     */
    public function uploadImage()
    {

        // Get the uploaded image instance.
        $image = UploadedFile::getInstance($this, 'image');

        // If no image was uploaded abort the upload
        if (empty($image)) {
            return false;
        }

        // Store a unique avatar name
        $this->avatar = Yii::$app->security->generateRandomString() . '.' . $image->extension;

        // The uploaded image instance
        return $image;

    }

    /**
     * Process deletion of avatar image
     *
     * @return boolean the status of deletion
     */
    public function deleteImage()
    {

        $file = $this->getImageFile();

        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }

        // if deletion successful, reset your file attributes
        $this->avatar = null;
        $this->image = null;

        return true;

    }
}
