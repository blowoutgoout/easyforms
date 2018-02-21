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

namespace app\components;

use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package app\components
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {

        $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {

            try {

                /*******************************
                /* Mailer
                /*******************************/

                // Set default transport class
                $transport = [
                    'class' => 'Swift_MailTransport',
                ];

                // Change transport class to SMTP if was selected
                if (isset($app->params['App.Mailer.transport']) && trim($app->params['App.Mailer.transport']) === 'smtp') {
                    $transport = [
                        'class' => 'Swift_SmtpTransport',
                        'host' => $app->settings->get("smtp.host"),
                        'username' => $app->settings->get("smtp.username"),
                        'password' => base64_decode($app->settings->get("smtp.password")),
                        'port' => $app->settings->get("smtp.port"),
                        'encryption' => $app->settings->get("smtp.encryption") == 'none'?
                            null :
                            $app->settings->get("smtp.encryption"),
                    ];
                }

                // Set mail queue component as mailer
                $app->set('mailer', [
                    'class' => 'app\components\queue\MailQueue',
                    'mailsPerRound' => 10,
                    'maxAttempts' => 3,
                    'transport' => $transport,
                    'messageConfig' => [
                        'charset' => 'UTF-8',
                    ]
                ]);

                /*******************************
                /* User session
                /*******************************/

                if (isset($app->user) && !$app->user->isGuest) {
                    /** @var \app\models\Profile $profile */
                    $profile = $app->user->identity->profile;

                    // Setting the timezone to the current users timezone
                    if (isset($profile->timezone)) {
                        $app->setTimeZone($profile->timezone);
                    }

                    // Setting the language to the current users language
                    if (isset($profile->language)) {
                        $app->language = $profile->language;
                    }
                }

            } catch (\Exception $e) {
                // Do nothing
            }

        });

        /*******************************
        /* Event Handlers
        /*******************************/

        $app->on(
            'app.form.updated',
            ['app\events\handlers\FormEventHandler', 'onFormUpdated']
        );

        $app->on(
            'app.form.submission.received',
            ['app\events\handlers\SubmissionEventHandler', 'onSubmissionReceived']
        );

        $app->on(
            'app.form.submission.accepted',
            ['app\events\handlers\SubmissionEventHandler', 'onSubmissionAccepted']
        );

        $app->on(
            'app.form.submission.rejected',
            ['app\events\handlers\SubmissionEventHandler', 'onSubmissionRejected']
        );

    }
}
