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

namespace app\modules\setup\models\forms;

use Yii;
use yii\web\Application;
use yii\web\ServerErrorHttpException;
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\models\Role;
use app\modules\setup\models\Account;
use app\modules\setup\models\Profile;
use app\modules\setup\models\Setting;

/**
 * User form
 */
class UserForm extends Model
{

    // User
    public $email;
    public $username;
    public $password;
    public $status;

    // Profile
    public $language;
    public $timezone;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // general email and username rules
            [['email', 'username'], 'string', 'max' => 255],
            [['email', 'username'], 'filter', 'filter' => 'trim'],
            [['email'], 'email'],
            [['username'], 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => Yii::t('setup', '{attribute} can contain only letters, numbers, and "_"')],
            [['email', 'username'], 'required'],
            [['email', 'username'], 'validateUniqueValue'],

            // password rules
            [['password'], 'string', 'min' => 3],
            [['password'], 'filter', 'filter' => 'trim'],
            [['password'], 'required'],

            // profile fields
            [['timezone', 'language'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id'     => Yii::t('setup', 'Role ID'),
            'status'      => Yii::t('setup', 'Status'),
            'email'       => Yii::t('setup', 'Email'),
            'username'    => Yii::t('setup', 'Username'),
            'password'    => Yii::t('setup', 'Password'),
            'language'    => Yii::t('setup', 'Language'),
            'timezone'    => Yii::t('setup', 'Timezone'),
        ];
    }

    /**
     * Save an administrator account in database
     *
     * @return bool
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     */
    public function save()
    {
        if ($this->validate()) {

            $transaction = Account::getDb()->beginTransaction();
            try {

                $account = new Account();
                $account->role_id = Role::ROLE_ADMIN;
                $account->status = User::STATUS_ACTIVE;
                $account->email = $this->email;
                $account->username = $this->username;
                $account->password = Yii::$app->security->generatePasswordHash($this->password);
                $account->auth_key = Yii::$app->security->generateRandomString();
                $account->access_token = Yii::$app->security->generateRandomString();
                $account->created_ip = Yii::$app->request->getUserIP();
                $account->created_at = date('Y-m-d H:i:s');
                $account->save();

                $profile = new Profile();
                $profile->user_id = $account->id;
                $profile->timezone = !empty($this->timezone) ? $this->timezone : null;
                $profile->language = Yii::$app->language;
                $profile->created_at = date('Y-m-d H:i:s');
                $profile->save();

                $setting = new Setting();
                $setting->type = 'string';
                $setting->category = 'app';
                $setting->key = 'purchaseCode';
                $setting->value = Yii::$app->session->get('purchase_code', '');
                $setting->status = 1;
                $setting->save();

                $transaction->commit();
            } catch (\Exception $e) {
                // Rolls back the transaction
                $transaction->rollBack();
                // Display a message
                Yii::$app->session->setFlash('danger', Yii::t('setup', 'There was an error creating your administrator account, please contact us.'));
                return false;
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Verify if the attribute is unique in database
     *
     * @param $attribute
     * @throws ServerErrorHttpException
     */
    public function validateUniqueValue($attribute)
    {
        $oldApp = Yii::$app;
        $webConfigFile = Yii::getAlias('@app/config/web.php');

        if (!file_exists($webConfigFile) || !is_array(($webConfig = require($webConfigFile)))) {
            throw new ServerErrorHttpException('Cannot find `'.
                Yii::getAlias('@app/config/console.php').
                '`. Please create and configure console config.');
        }

        Yii::$app = new Application($webConfig);

        $count = Account::find()
            ->where([$attribute => $this->$attribute])
            ->count();
        Yii::$app = $oldApp;

        if ($count > 0) {
            $this->addError($attribute, Yii::t('setup', 'This value has already been taken.'));
        }
    }
}
