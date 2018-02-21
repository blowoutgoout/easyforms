<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.3.3
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\cron\CronExpression;
use app\components\analytics\Analytics;

/**
 * Class CronController
 *
 * @package app\controllers
 */
class CronController extends Controller
{

    /**
     * @var string the default controller action.
     */
    public $defaultAction = 'run';

    /**
     * Run cron commands
     *
     * @param $cron_key
     * @return string
     */
    public function actionRun($cron_key)
    {
        if (isset(Yii::$app, Yii::$app->params, Yii::$app->params['App.Cron.cronKey']) &&
            Yii::$app->params['App.Cron.cronKey'] === $cron_key) {

            // NOTE: Linux Cron must be configured to every minute, no less

            // By default, update analytics every day
            $cron = CronExpression::factory(Yii::$app->params['App.Analytics.cronExpression']);

            // Update analytics
            if ($cron->isDue()) {
                Analytics::aggregate();
                Yii::info("Analytics has successfully updated the stats tables.");
            }

            // By default, process mail queue every minute
            $cron = CronExpression::factory(Yii::$app->params['App.Mailer.cronExpression']);
            // Process queue
            if ($cron->isDue()) {
                /** @var \app\components\queue\MailQueue $mailer */
                $mailer = Yii::$app->mailer;
                $success = $mailer->process();
                if ($success) {
                    // if all messages are successfully sent out
                    Yii::info('All e-mails are successfully sent out.');
                } else {
                    Yii::error('Error sending e-mails.');
                }
            }
        }
        Yii::$app->response->setStatusCode(200);
        return '';
    }

}