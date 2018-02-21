<?php
/**
 * @copyright Copyright (c) 2014 HimikLab
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @link https://github.com/himiklab/yii2-recaptcha-widget
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace app\components\validators;

use Yii;
use yii\validators\Validator;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

class RecaptchaValidator extends Validator
{
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    const CAPTCHA_RESPONSE_FIELD = 'g-recaptcha-response';

    /** @inheritdoc */
    public $skipOnEmpty = false;
    /** @var string */
    public $emptyMessage;
    /** @inheritdoc */
    public $message;
    /** @var string The shared key between your site and ReCAPTCHA. */
    public $secret;

    /** @inheritdoc */
    public function init()
    {
        parent::init();

        $this->emptyMessage = Yii::t('app', 'The captcha is a required field.');
        $this->message = Yii::t('app', 'The captcha code you entered was incorrect.');

        if (empty($this->secret)) {
            $this->secret = Yii::$app->settings->get("app.reCaptchaSecret");
            if (empty($this->secret)) {
                throw new InvalidConfigException(Yii::t('app', 'ReCaptcha Secret Key is empty.'));
            }
        }
    }

    /**
     * @param string $value
     * @return array|null
     * @throws \Exception
     */
    protected function validateValue($value)
    {
        if (empty($value)) {
            return [$this->emptyMessage, []];
        }
        $request = self::SITE_VERIFY_URL . '?' . http_build_query(
            [
                'secret' => $this->secret,
                'response' => $value,
                'remoteip' => Yii::$app->request->userIP
            ]
        );
        $response = $this->getResponse($request);
        if (!isset($response['success'])) {
            throw new \Exception(Yii::t('app', 'Invalid reCAPTCHA verification response.'));
        }

        // Save reCaptcha response in session
        Yii::$app->session['reCaptcha'] = $response['success'];

        return $response['success'] ? null : [$this->message, []];
    }

    /**
     * @param string $request
     * @return mixed
     */
    protected function getResponse($request)
    {
        $response = file_get_contents($request);
        return Json::decode($response, true);
    }
}
