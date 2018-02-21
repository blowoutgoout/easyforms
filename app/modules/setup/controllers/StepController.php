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

namespace app\modules\setup\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Cookie;
use GuzzleHttp\Client;
use app\modules\setup\models\forms\DBForm;
use app\modules\setup\models\forms\UserForm;
use app\modules\setup\helpers\SetupHelper;

class StepController extends Controller
{
    public $layout = 'setup';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        Yii::$app->language = isset(Yii::$app->request->cookies['language']) ? (string)Yii::$app->request->cookies['language'] : 'en-US';

        if (!parent::beforeAction($action)) {
            return false;
        }

        if ($this->action->id != '1') {
            if (!Yii::$app->session->has('purchase_code')) {
                Yii::$app->session->setFlash('warning', Yii::t('setup', 'Please enter a valid purchase code'));
                return $this->redirect(['step/1']);
            }
        }

        return true; // or false to not run the action
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function action1()
    {
        if ($language = Yii::$app->request->post('language')) {

            Yii::$app->language = $language;

            $languageCookie = new Cookie([
                'name' => 'language',
                'value' => $language,
                'expire' => time() + 60 * 60 * 24, // 1 day
            ]);

            Yii::$app->response->cookies->add($languageCookie);

            $purchase_code = Yii::$app->request->post('purchase_code', '');

            if (trim($purchase_code) == '') {
                Yii::$app->session->setFlash('warning', Yii::t('setup', 'Please enter a valid purchase code'));
                return $this->redirect(['step/1']);
            }

            try {

                $client = new Client();

                $response = $client->post(base64_decode(SetupHelper::$purchaseCode), [
                    'future'          => true,
                    'headers'         => ['User-Agent' => Yii::$app->name],
                    'body'            => [
                        'purchase_code' => $purchase_code
                    ],
                    'allow_redirects' => false,
                    'timeout'         => 15
                ]);

                if ($body = $response->getBody()) {
                    $body = json_decode($body);
                    if ($body->status == 1) {
                        Yii::$app->session->set('purchase_code', $purchase_code);
                        return $this->redirect(['step/2']);
                    } else {
                        if (Yii::$app->session->has('purchase_code')) {
                            Yii::$app->session->remove('purchase_code');
                        }
                        Yii::$app->session->setFlash('warning', Yii::t('setup', 'Please enter a valid purchase code'));
                        return $this->redirect(['step/1']);
                    }
                }

            } catch (\GuzzleHttp\Exception\ConnectException $e) {
                Yii::$app->session->setFlash('warning', Yii::t('setup', 'We can\'t activate your purchase code. Please check your internet connection.'));
                return $this->redirect(['step/1']);
            }

        }

        return $this->render('1');
    }

    public function action2()
    {
        return $this->render('2');
    }

    public function action3()
    {
        $dbForm = new DBForm();
        $connectionOk = false;

        if ($dbForm->load(Yii::$app->request->post()) && $dbForm->validate()) {
            if ($dbForm->testConnection()) {
                if (isset($_POST['test'])) {
                    $connectionOk = true;
                    Yii::$app->session->setFlash('success', Yii::t('setup', 'Database connection - ok'));
                }
                if (isset($_POST['save'])) {
                    $config = SetupHelper::createDatabaseConfig($dbForm->getAttributes());
                    if (SetupHelper::createDatabaseConfigFile($config) === true) {
                        return $this->render('4');
                    }
                    Yii::$app->session->setFlash('warning', Yii::t('setup', 'Unable to create db config file'));
                }
            }
        }

        return $this->render('3', ['model' => $dbForm, 'connectionOk' => $connectionOk]);
    }

    public function action4()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Check if database was successfully installed

            $result = SetupHelper::executeSqlCommands();

            if (isset($result['success']) && $result['success'] === 0) {
                $result = SetupHelper::runMigrations();
            }

            return $result;
        }

        return '';
    }

    public function action5()
    {
        $userForm = new UserForm();

        if ($userForm->load(Yii::$app->request->post()) && $userForm->save()) {
            return $this->redirect(['step/6']);
        }

        return $this->render('5', [
            'model' => $userForm,
        ]);
    }

    public function action6()
    {
        $client = new Client();

        // With Friendly Urls
        $cronUrl = Url::home(true) . 'cron?cron_key='.Yii::$app->params['App.Cron.cronKey'];

        $response = $client->get($cronUrl, [
            'allow_redirects' => false,
            'timeout'         => 15
        ]);

        if (json_decode($response->getStatusCode()) != 200) {
            // Without Friendly Urls
            $url = Url::to([
                '/cron',
                'cron_key' => Yii::$app->params['App.Cron.cronKey'],
            ], true);
            $cronUrl = str_replace("install","index",$url);
        }

        return $this->render('6', [
            'cronUrl' => $cronUrl
        ]);
    }
}
