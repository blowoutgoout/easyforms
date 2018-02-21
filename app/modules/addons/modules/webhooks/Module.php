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

namespace app\modules\addons\modules\webhooks;

use Yii;
use yii\helpers\Json;
use GuzzleHttp\Client;
use app\modules\addons\EventManagerInterface;
use app\modules\addons\modules\webhooks\models\Webhook;
use app\helpers\Html;

class Module extends \yii\base\Module implements EventManagerInterface
{

    public $id = "webhooks";
    public $defaultRoute = 'admin/index';
    public $controllerLayout = '@app/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Set up i8n of this add-on
        if (empty(Yii::$app->i18n->translations['webhooks'])) {
            Yii::$app->i18n->translations['webhooks'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@addons/webhooks/messages',
                //'forceTranslation' => true,
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function attachGlobalEvents()
    {
        return [
            'app.form.submission.accepted' => function ($event) {
                $this->sendSubmissionData($event);
            }
        ];
    }

    /**
     * @inheritdoc
     */
    public function attachClassEvents()
    {
        return [];
    }

    /**
     * Send the form submission data through an HTTP POST request
     * either as URL encoded form data or as a JSON string
     * depending on the format selected in the Webhook configuration
     *
     * @param $event
     */
    public function sendSubmissionData($event)
    {

        if (isset($event, $event->form, $event->form->id, $event->submission)) {

            $webhooks = Webhook::findAll(['form_id' => $event->form->id, 'status' => 1]);

            $client = new Client();
            $body = $event->submission->getSubmissionData();

            foreach ($webhooks as $webhook) {

                // Add Handshake Key
                if (!empty($webhook->handshake_key)) {
                    $body = $body + ['handshake_key' => $webhook->handshake_key];
                }

                // Add Json Format
                if ($webhook->json === 1) {
                    $body = Json::encode($body);
                }

                // Send HTTP POST request asynchronously
                $response = $client->post($webhook->url, [
                    'future'          => true,
                    'headers'         => ['User-Agent' => Yii::$app->name],
                    'body'            => $body,
                    'allow_redirects' => false,
                    'timeout'         => 5
                ]);

                // Call the function when the response completes
                $response->then(function ($response) {
                    // echo $response->getStatusCode();
                });
            }
        }
    }
}
