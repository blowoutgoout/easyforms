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

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use app\models\forms\CaptchaForm;

/**
 * Class UserController
 * @package app\controllers
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        // Default Access
        $allowedActions = ['login', 'forgot', 'reset'];
        $deniedActions = ['register', 'login-email', 'login-callback'];

        // Enable User Registration
        $anyoneCanRegister = (bool) Yii::$app->settings->get('app.anyoneCanRegister');
        if ($anyoneCanRegister) {
            $allowedActions = ['login', 'register', 'forgot', 'reset', 'login-email', 'login-callback'];
            $deniedActions = [];
        }

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'confirm', 'resend', 'logout', 'captcha'],
                        'allow'   => true,
                        'roles'   => ['?', '@'],
                    ],
                    [
                        'actions' => ['account', 'profile', 'change-username', 'change-email',
                            'change-password', 'avatar-delete', 'resend-change', 'cancel'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                    [
                        'actions' => $allowedActions,
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => $deniedActions,
                        'allow'   => false,
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'backColor' => 0x313941,
                'foreColor' => 0xFFFFFF,
            ],
        ];
    }

    /**
     * Display index - debug page or profile page
     */
    public function actionIndex()
    {
        $this->layout = 'public';

        if (defined('YII_DEBUG') && YII_DEBUG) {
            $actions = Yii::$app->getModule("user")->getActions();
            return $this->render('index', ["actions" => $actions]);
        } elseif (Yii::$app->user->isGuest) {
            return $this->redirect(["/user/login"]);
        } else {
            return $this->redirect(["/user/account"]);
        }
    }

    /**
     * Display login page
     */
    public function actionLogin()
    {
        // Validate IP Address
        // $ip = Yii::$app->getRequest()->getUserIP();
        $ip = getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                    getenv('HTTP_FORWARDED_FOR')?:
                        getenv('HTTP_FORWARDED')?:
                            getenv('REMOTE_ADDR');
        $validIps = isset(Yii::$app->params['App.User.validIps']) ? Yii::$app->params['App.User.validIps'] : false;
        if ($validIps && is_array($validIps) && count($validIps) > 0 && !in_array($ip, $validIps)) {
            throw new NotFoundHttpException(Yii::t("app", "The requested page does not exist."));
        }

        $this->layout = 'public';

        /** @var \app\modules\user\models\forms\LoginForm $model */
        $model = Yii::$app->getModule("user")->model("LoginForm");
        // load post data and login
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            $returnUrl = $this->performLogin($model->getUser(), $model->rememberMe);
            return $this->redirect($returnUrl);
        }

        // Display view depending of application configuration
        $anyoneCanRegister = (bool) Yii::$app->settings->get('app.anyoneCanRegister');
        if ($anyoneCanRegister) {
            return $this->render('membership', compact("model"));
        }
        return $this->render('login', compact("model"));
    }

    /**
     * Login/register via email
     */
    public function actionLoginEmail()
    {
        $loginWithoutPassword = (bool) Yii::$app->settings->get('app.loginWithoutPassword');
        if (!$loginWithoutPassword) {
            throw new NotFoundHttpException(Yii::t("app", "The requested page does not exist."));
        }

        $this->layout = 'public';

        /** @var \app\modules\user\models\forms\LoginEmailForm $loginEmailForm */
        $loginEmailForm = Yii::$app->getModule("user")->model("LoginEmailForm");
        $captchaForm = new CaptchaForm();

        // load post data and validate
        $post = Yii::$app->request->post();

        // validate captcha
        $useCaptcha = (bool) Yii::$app->settings->get('app.useCaptcha');
        if ($useCaptcha && $captchaForm->load($post) && $captchaForm->validate()) {
            return ActiveForm::validate($captchaForm);
        }

        if ($loginEmailForm->load($post) && $loginEmailForm->sendEmail()) {
            $user = $loginEmailForm->getUser();
            $message  = $user ? Yii::t('app', 'Log In link sent.') : Yii::t('app', 'Sign Up link sent.');
            $message .= ' ' . Yii::t('app', 'Please check your email.');
            Yii::$app->session->setFlash("Login-success", Yii::t("user", $message));
        }

        return $this->render("loginEmail", compact("loginEmailForm", "captchaForm"));
    }

    /**
     * Login/register callback via email
     */
    public function actionLoginCallback($token)
    {
        $loginWithoutPassword = (bool) Yii::$app->settings->get('app.loginWithoutPassword');
        if (!$loginWithoutPassword) {
            throw new NotFoundHttpException(Yii::t("app", "The requested page does not exist."));
        }

        $this->layout = 'public';

        /** @var \app\modules\user\models\User $user */
        /** @var \app\modules\user\models\Profile $profile */
        /** @var \app\models\Role $role */
        /** @var \app\modules\user\models\UserToken $userToken */

        $user = Yii::$app->getModule("user")->model("User");
        $profile = Yii::$app->getModule("user")->model("Profile");
        $userToken = Yii::$app->getModule("user")->model("UserToken");

        // check token and log user in directly
        $userToken = $userToken::findByToken($token, $userToken::TYPE_EMAIL_LOGIN);
        if ($userToken && $userToken->user) {
            $returnUrl = $this->performLogin($userToken->user);
            $userToken->delete();
            return $this->redirect($returnUrl);
        }

        // load post data
        $post = Yii::$app->request->post();

        if ($userToken && $user->load($post)) {

            // ensure that email is taken from the $userToken (and not from user input)
            $user->email = $userToken->data;

            // validate and register
            $profile->load($post);

            if ($user->validate() && $profile->validate()) {
                $role = Yii::$app->getModule("user")->model("Role");
                // Get default user role
                $userRole = $role::ROLE_USER;
                $defaultUserRole = Yii::$app->settings->get('app.defaultUserRole');
                if ($defaultUserRole >= $role::ROLE_ADMIN &&
                    $defaultUserRole <= $role::ROLE_ADVANCED_USER) {
                    $userRole = $defaultUserRole;
                }
                $user->setRegisterAttributes($userRole, $user::STATUS_ACTIVE)->save();
                $profile->setUser($user->id)->save();

                // log user in and delete token
                $returnUrl = $this->performLogin($user);
                $userToken->delete();
                return $this->redirect($returnUrl);
            }
        }

        $user->email = $userToken ? $userToken->data : null;
        return $this->render("loginCallback", compact("user", "profile", "userToken"));
    }

    /**
     * Perform the login
     */
    protected function performLogin($user, $rememberMe = true)
    {
        // log user in
        $loginDuration = $rememberMe ? Yii::$app->getModule("user")->loginDuration : 0;
        Yii::$app->user->login($user, $loginDuration);

        // check for a valid returnUrl (to prevent a weird login bug)
        //   https://github.com/amnah/yii2-user/issues/115
        $loginRedirect = Yii::$app->getModule("user")->loginRedirect;
        $returnUrl = Yii::$app->user->getReturnUrl($loginRedirect);
        if (strpos($returnUrl, "user/login") !== false || strpos($returnUrl, "user/logout") !== false) {
            $returnUrl = null;
        }

        return $returnUrl;
    }

    /**
     * Log user out and redirect
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        // handle redirect
        $logoutRedirect = Yii::$app->getModule("user")->logoutRedirect;
        if ($logoutRedirect) {
            return $this->redirect($logoutRedirect);
        }
        return $this->goHome();
    }

    /**
     * Display registration page
     */
    public function actionRegister()
    {
        $this->layout = 'public';

        /** @var \app\modules\user\models\User $user */
        /** @var \app\modules\user\models\Profile $profile */
        /** @var \app\models\Role $role */

        // set up new user/profile objects
        $user = Yii::$app->getModule("user")->model("User", ["scenario" => "register"]);
        $profile = Yii::$app->getModule("user")->model("Profile");
        $captchaForm = new CaptchaForm();

        // load post data
        $post = Yii::$app->request->post();
        if ($user->load($post)) {

            // ensure profile data gets loaded
            $profile->load($post);

            // validate for ajax request
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user, $profile);
            }

            // validate captcha
            $useCaptcha = (bool) Yii::$app->settings->get('app.useCaptcha');
            if ($useCaptcha && $captchaForm->load($post) && !$captchaForm->validate()) {
                return ActiveForm::validate($captchaForm);
            }

            // validate for normal request
            if ($user->validate() && $profile->validate()) {

                // Get default user role
                $role = Yii::$app->getModule("user")->model("Role");
                $userRole = $role::ROLE_USER;
                $defaultUserRole = Yii::$app->settings->get('app.defaultUserRole');
                if ($defaultUserRole >= $role::ROLE_ADMIN &&
                    $defaultUserRole <= $role::ROLE_ADVANCED_USER) {
                    $userRole = $defaultUserRole;
                }

                // perform registration
                $user->setRegisterAttributes($userRole)->save();
                $profile->setUser($user->id)->save();
                $this->afterRegister($user);

                // set flash
                // don't use $this->refresh() because user may automatically be logged in and get 403 forbidden
                $successText = Yii::t("app", "Successfully registered [ {displayName} ]", [
                    "displayName" => $user->getDisplayName()
                ]);
                $guestText = "";
                if (Yii::$app->user->isGuest) {
                    $guestText = " - " . Yii::t("app", "Please check your email to confirm your account");
                }
                Yii::$app->session->setFlash("Register-success", $successText . $guestText);
            }
        }

        return $this->render("register", compact("user", "profile", "captchaForm"));
    }

    /**
     * Process data after registration
     * @param \app\modules\user\models\User $user
     */
    protected function afterRegister($user)
    {
        /** @var \app\modules\user\models\UserToken $userToken */
        $userToken = Yii::$app->getModule('user')->model("UserToken");

        // determine userToken type to see if we need to send email
        $userTokenType = null;
        if ($user->status == $user::STATUS_INACTIVE) {
            $userTokenType = $userToken::TYPE_EMAIL_ACTIVATE;
        } elseif ($user->status == $user::STATUS_UNCONFIRMED_EMAIL) {
            $userTokenType = $userToken::TYPE_EMAIL_CHANGE;
        }

        // check if we have a userToken type to process, or just log user in directly
        if ($userTokenType) {
            $userToken = $userToken::generate($user->id, $userTokenType);
            if (!$numSent = $user->sendEmailConfirmation($userToken)) {

                // handle email error
                //Yii::$app->session->setFlash("Email-error", "Failed to send email");
            }
        } else {
            Yii::$app->user->login($user, Yii::$app->getModule("user")->loginDuration);
        }
    }

    /**
     * Confirm email
     */
    public function actionConfirm($token)
    {
        $this->layout = 'public';

        /** @var \app\modules\user\models\UserToken $userToken */
        /** @var \app\modules\user\models\User $user */

        // search for userToken
        $success = false;
        $email = "";
        $userToken = Yii::$app->getModule("user")->model("UserToken");
        $userToken = $userToken::findByToken($token, [$userToken::TYPE_EMAIL_ACTIVATE, $userToken::TYPE_EMAIL_CHANGE]);
        if ($userToken) {

            // find user and ensure that another user doesn't have that email
            //   for example, user registered another account before confirming change of email
            $user = Yii::$app->getModule("user")->model("User");
            $user = $user::findOne($userToken->user_id);
            $newEmail = $userToken->data;
            if ($user->confirm($newEmail)) {
                $success = true;
            }

            // set email and delete token
            $email = $newEmail ?: $user->email;
            $userToken->delete();
        }

        return $this->render("confirm", compact("userToken", "success", "email"));
    }

    /**
     * Forgot password
     */
    public function actionForgot()
    {
        $this->layout = 'public';

        /** @var \app\modules\user\models\forms\ForgotForm $model */
        // load post data and send email
        $model = Yii::$app->getModule("user")->model("ForgotForm");
        if ($model->load(Yii::$app->request->post()) && $model->sendForgotEmail()) {
            // set flash (which will show on the current page)
            Yii::$app->session->setFlash(
                "Forgot-success",
                Yii::t("app", "Instructions to reset your password have been sent to your e-mail address.")
            );
        }

        return $this->render("forgot", compact("model"));
    }

    /**
     * Resend email confirmation
     */
    public function actionResend()
    {
        $this->layout = 'public';

        /** @var \app\modules\user\models\forms\ResendForm $model */

        // load post data and send email
        $model = Yii::$app->getModule("user")->model("ResendForm");
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {

            // set flash (which will show on the current page)
            Yii::$app->session->setFlash("success", Yii::t("app", "Confirmation email resent"));
        }

        return $this->render("resend", compact("model"));
    }

    /**
     * Reset password
     */
    public function actionReset($token)
    {
        $this->layout = 'public';

        /** @var \app\modules\user\models\User $user */
        /** @var \app\modules\user\models\UserToken $userToken */

        // get user token and check expiration
        $userToken = Yii::$app->getModule("user")->model("UserToken");
        $userToken = $userToken::findByToken($token, $userToken::TYPE_PASSWORD_RESET);
        if (!$userToken) {
            return $this->render('reset', ["invalidToken" => true]);
        }

        // get user and set "reset" scenario
        $success = false;
        $user = Yii::$app->getModule("user")->model("User");
        $user = $user::findOne($userToken->user_id);
        $user->setScenario("reset");

        // load post data and reset user password
        if ($user->load(Yii::$app->request->post()) && $user->save()) {

            // delete userToken and set success = true
            $userToken->delete();
            $success = true;
        }

        return $this->render('reset', compact("user", "success"));
    }

    /**
     * Account
     */
    public function actionAccount()
    {
        /** @var \app\models\User $user */
        /** @var \app\modules\user\models\UserToken $userToken */

        $this->layout = '/admin'; // In @app/views/layouts

        // set up user and load post data
        $user = Yii::$app->user->identity;
        $user->setScenario("account");
        $loadedPost = $user->load(Yii::$app->request->post());

        // validate for ajax request
        if ($loadedPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        // validate for normal request
        $userToken = Yii::$app->getModule('user')->model("UserToken");
        if ($loadedPost && $user->validate()) {

            // check if user changed his email
            $newEmail = $user->checkEmailChange();
            if ($newEmail) {
                $userToken = $userToken::generate($user->id, $userToken::TYPE_EMAIL_CHANGE, $newEmail);
                if (!$numSent = $user->sendEmailConfirmation($userToken)) {

                    // handle email error
                    //Yii::$app->session->setFlash("Email-error", "Failed to send email");
                }
            }

            // save, set flash, and refresh page
            $user->save(false);
            Yii::$app->session->setFlash("success", Yii::t("app", "Your account information has been updated."));
            return $this->refresh();
        }

        return $this->render("account", compact("user", "userToken"));
    }

    /**
     * Profile
     */
    public function actionProfile()
    {
        /** @var \app\models\Profile $profile */

        $this->layout = '/admin'; // In @app/views/layouts

        // set up profile and load post data
        $profile = Yii::$app->user->identity->profile;
        $loadedPost = $profile->load(Yii::$app->request->post());

        // validate for ajax request
        if ($loadedPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($profile);
        }

        // validate for normal request
        if ($loadedPost && $profile->validate()) {

            // Old image
            $oldImage = $profile->getImageFile();

            // Process uploaded image file instance
            /** @var UploadedFile $image */
            $image = $profile->uploadImage();

            if ($profile->save()) {
                // Upload only if valid uploaded image instance found
                if ($image !== false) {
                    // Delete old image and overwrite
                    @unlink($oldImage);
                    $path = $profile->getImageFile();
                    $image->saveAs($path);
                }
                Yii::$app->session->setFlash("success", Yii::t("app", "Your profile has been updated"));
                return $this->refresh();
            }
        }

        // render
        return $this->render("profile", [
            'profile' => $profile,
        ]);
    }

    /**
     * Change Username
     *
     * @return bool
     */
    public function actionChangeUsername()
    {
        /** @var \app\models\User $user */
        /** @var \app\modules\user\models\UserToken $userToken */

        $this->layout = '/admin'; // In @app/views/layouts

        // set up user and load post data
        $user = Yii::$app->user->identity;
        $user->setScenario("account");
        $loadedPost = $user->load(Yii::$app->request->post());

        // validate for ajax request
        if ($loadedPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        // validate for normal request
        if ($loadedPost && $user->validate()) {
            // save, set flash, and refresh page
            $user->save(false);
            Yii::$app->session->setFlash("success", Yii::t("app", "Your username has been updated"));
            return $this->refresh();
        }

        // render
        return $this->render("username", [
            'user' => $user,
        ]);

    }

    /**
     * Change Email Address
     */
    public function actionChangeEmail()
    {
        /** @var \app\models\User $user */
        /** @var \app\modules\user\models\UserToken $userToken */

        $this->layout = '/admin'; // In @app/views/layouts

        // set up user and load post data
        $user = Yii::$app->user->identity;
        $user->setScenario("account");
        $loadedPost = $user->load(Yii::$app->request->post());

        // validate for ajax request
        if ($loadedPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        // validate for normal request
        $userToken = Yii::$app->getModule("user")->model("UserToken");

        // validate for normal request
        if ($loadedPost && $user->validate()) {

            // check if user changed his email
            $newEmail = $user->checkEmailChange();
            if ($newEmail) {
                $userToken = $userToken::generate($user->id, $userToken::TYPE_EMAIL_CHANGE, $newEmail);
                if (!$numSent = $user->sendEmailConfirmation($userToken)) {

                    // handle email error
                    //Yii::$app->session->setFlash("Email-error", "Failed to send email");
                }
            }

            // save, set flash, and refresh page
            $user->save(false);
            Yii::$app->session->setFlash("success", Yii::t("app", "Your email has been updated"));
            return $this->refresh();
        }

        // render
        return $this->render("email", compact("user", "userToken"));
    }

    /**
     * Change Password
     */
    public function actionChangePassword()
    {
        /** @var \app\models\User $user */
        /** @var \app\modules\user\models\UserToken $userToken */

        $this->layout = '/admin'; // In @app/views/layouts

        // set up user and load post data
        $user = Yii::$app->user->identity;
        $user->setScenario("update");
        $loadedPost = $user->load(Yii::$app->request->post());

        // validate for ajax request
        if ($loadedPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        // validate for normal request
        if ($loadedPost && $user->validate()) {
            // save, set flash, and refresh page
            $user->save(false);
            Yii::$app->session->setFlash("success", Yii::t("app", "Your password has been updated"));
            return $this->refresh();
        }

        // render
        return $this->render("password", [
            'user' => $user,
        ]);
    }

    /**
     * Delete avatar
     *
     * @return bool|string
     */
    public function actionAvatarDelete()
    {

        // Delete for ajax request
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            // Set up profile and delete its avatar
            /** @var \app\models\Profile $profile */
            $profile = Yii::$app->user->identity->profile;
            if (!$profile->deleteImage()) {
                Yii::$app->session->setFlash(
                    'error',
                    Yii::t("app", "Has occurred an error deleting your profile image.")
                );
                return false;
            }
            $profile->save(false);
            Yii::$app->session->setFlash("success", Yii::t("app", "Your profile image has been deleted."));
            return true;
        }

        return '';
    }

    /**
     * Resend email change confirmation
     */
    public function actionResendChange()
    {
        /** @var \app\modules\user\models\User $user */
        /** @var \app\modules\user\models\UserToken $userToken */

        // find userKey of type email change
        $user    = Yii::$app->user->identity;
        $userToken = Yii::$app->getModule("user")->model("UserToken");
        $userToken = $userToken::findByUser($user->id, $userToken::TYPE_EMAIL_CHANGE);
        if ($userToken) {

            // send email and set flash message
            $user->sendEmailConfirmation($userToken);
            Yii::$app->session->setFlash("success", Yii::t("app", "Confirmation email resent"));
        }

        // redirect to profile page
        return $this->redirect(["/user/profile"]);
    }

    /**
     * Cancel email change
     */
    public function actionCancel()
    {
        /** @var \app\modules\user\models\User    $user */
        /** @var \app\modules\user\models\UserToken $userToken */

        // find userKey of type email change
        $user = Yii::$app->user->identity;
        $userToken = Yii::$app->getModule("user")->model("UserToken");
        $userToken = $userToken::findByUser($user->id, $userToken::TYPE_EMAIL_CHANGE);
        if ($userToken) {
            $userToken->delete();
            Yii::$app->session->setFlash("success", Yii::t("app", "Email change cancelled"));
        }

        // go to profile page
        return $this->redirect(["/user/profile"]);
    }
}
