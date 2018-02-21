<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.1
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\modules\update\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Cookie;
use app\modules\update\helpers\SetupHelper;

class StepController extends Controller
{
    public $layout = 'setup';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        Yii::$app->language = isset(Yii::$app->request->cookies['language']) ?
            (string)Yii::$app->request->cookies['language'] : 'en-US';

        if (!parent::beforeAction($action)) {
            return false;
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

    /**
     * Language selector
     *
     * @return string
     */
    public function action1()
    {
        if (Yii::$app->request->post('language')) {

            $language = Yii::$app->request->post('language');
            Yii::$app->language = $language;

            $languageCookie = new Cookie([
                'name' => 'language',
                'value' => $language,
                'expire' => time() + 60 * 60 * 24, // 1 day
            ]);

            Yii::$app->response->cookies->add($languageCookie);

            return $this->redirect(['2']);
        }

        return $this->render('1');
    }

    /**
     * Requirements
     *
     * @return string
     */
    public function action2()
    {
        return $this->render('2');
    }

    /**
     * Run update
     *
     * @return string
     */
    public function action3()
    {
        return $this->render('3');
    }

    /**
     * Run Migrations vía ajax request
     *
     * @return int|string
     */
    public function action4()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $result = SetupHelper::runMigrations();
            return $result;
        }

        return '';
    }

    /**
     * Congratulations
     *
     * @return string
     */
    public function action5()
    {
        return $this->render('5');
    }
}
