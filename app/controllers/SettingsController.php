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
use yii\base\Model;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_TransportException;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\Setting;
use app\helpers\FileHelper;
use app\components\console\Console;

/**
 * Class SettingsController
 * @package app\controllers
 */
class SettingsController extends Controller
{

    public $defaultAction = 'site';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function () {
                            // Check for admin permission
                            // Note: Check for Yii::$app->user first because it doesn't exist in console commands
                            if (!empty(Yii::$app->user) && Yii::$app->user->can("admin")) {
                                return true;
                            }

                            // By Default, Denied Access
                            return false;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Update App Settings
     *
     * @return string
     */
    public function actionSite()
    {

        $this->layout = 'admin'; // In @app/views/layouts
        if (Yii::$app->request->post()) {
            Yii::$app->settings->set('app.name', Yii::$app->request->post('app_name', Yii::$app->settings->get('app.name')));
            Yii::$app->settings->set('app.description', Yii::$app->request->post('app_description', Yii::$app->settings->get('app.description')));
            Yii::$app->settings->set('app.adminEmail', Yii::$app->request->post('app_adminEmail', Yii::$app->settings->get('app.adminEmail')));
            Yii::$app->settings->set('app.supportEmail', Yii::$app->request->post('app_supportEmail', Yii::$app->settings->get('app.supportEmail')));
            Yii::$app->settings->set('app.noreplyEmail', Yii::$app->request->post('app_noreplyEmail', Yii::$app->settings->get('app.noreplyEmail')));
            Yii::$app->settings->set('app.reCaptchaSiteKey', Yii::$app->request->post('app_reCaptchaSiteKey', Yii::$app->settings->get('app.reCaptchaSiteKey')));
            Yii::$app->settings->set('app.reCaptchaSecret', Yii::$app->request->post('app_reCaptchaSecret', Yii::$app->settings->get('app.reCaptchaSecret')));

            // Membership
            $anyoneCanRegister = Yii::$app->request->post('app_anyoneCanRegister', null);
            $useCaptcha = Yii::$app->request->post('app_useCaptcha', null);
            $loginWithoutPassword = Yii::$app->request->post('app_loginWithoutPassword', null);
            Yii::$app->settings->set('app.anyoneCanRegister', is_null($anyoneCanRegister) ? 0 : 1);
            Yii::$app->settings->set('app.useCaptcha', is_null($useCaptcha) ? 0 : 1);
            Yii::$app->settings->set('app.loginWithoutPassword', is_null($loginWithoutPassword) ? 0 : 1);
            Yii::$app->settings->set('app.defaultUserRole', Yii::$app->request->post('app_defaultUserRole', Yii::$app->settings->get('app.defaultUserRole')));

            // Show success alert
            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t('app', 'The site settings have been successfully updated.')
            );
        }

        return $this->render('site');
    }

    public function actionMail()
    {

        if (isset(Yii::$app->params['App.Mailer.transport']) && trim(Yii::$app->params['App.Mailer.transport']) !== 'smtp') {
            throw new NotFoundHttpException(Yii::t("app", "The requested page does not exist."));
        }

        $this->layout = 'admin'; // In @app/views/layouts
        $settings = Setting::find()->where(['category' => 'smtp'])->orderBy('id')->all();

        if (Model::loadMultiple($settings, Yii::$app->request->post()) && Model::validateMultiple($settings)) {
            try {

                // Get settings
                $post = Yii::$app->request->post();
                $host = $post['Setting'][0]['value'];
                $port = $post['Setting'][1]['value'];
                $encryption = $post['Setting'][2]['value'] === "none" ? null : $post['Setting'][2]['value'];
                $username = $post['Setting'][3]['value'];
                $password = $post['Setting'][4]['value'];

                // Test smtp connection
                $transport = Swift_SmtpTransport::newInstance($host, $port, $encryption);
                $transport->setUsername($username);
                $transport->setPassword($password);
                $mailer = Swift_Mailer::newInstance($transport);
                $mailer->getTransport()->start();

                // Save settings
                /** @var \app\models\Setting $setting */
                foreach ($settings as $setting) {
                    $setting->save(false);
                }

                // Show success alert
                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app', 'The smtp server settings have been successfully updated.')
                );

            } catch (Swift_TransportException $e) {

                // Show error alert
                Yii::$app->getSession()->setFlash(
                    'danger',
                    $e->getMessage()
                );

            }
        }

        return $this->render('mail', ['settings' => $settings]);
    }

    public function actionPerformance()
    {
        $this->layout = 'admin'; // In @app/views/layouts

        if ($post = Yii::$app->request->post()) {

            // Run cron
            if (isset($post['action']) && $post['action'] === 'cron') {
                Console::run('cron');
                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app', 'Cron ran successfully.')
                );
            }

            // Refresh cache & assets
            if (isset($post['action']) && $post['action'] === 'cache') {

                $writable = true;

                $subdirectories = FileHelper::scandir(Yii::getAlias('@runtime/cache'));

                foreach ($subdirectories as $subdirectory) {
                    if (!is_writable(Yii::getAlias('@runtime/cache') . DIRECTORY_SEPARATOR . $subdirectory)) {
                        $writable = false;
                    }
                }

                // Flush all cache
                $flushed = Yii::$app->cache->flush();

                // Remove all assets
                foreach (glob(Yii::$app->assetManager->basePath . DIRECTORY_SEPARATOR . '*') as $asset) {
                    if (is_link($asset)) {
                        @unlink($asset);
                    } elseif (is_dir($asset)) {
                        FileHelper::removeDirectory($asset);
                    } else {
                        @unlink($asset);
                    }
                }

                // Show success alert
                if ($writable && $flushed) {
                    Yii::$app->getSession()->setFlash(
                        'success',
                        Yii::t('app', 'The cache and assets have been successfully refreshed.')
                    );
                } else {
                    Yii::$app->getSession()->setFlash(
                        'danger',
                        Yii::t('app', 'There was a problem clearing the cache. Please retry later.')
                    );
                }
            }
        }

        return $this->render('performance');
    }
}
