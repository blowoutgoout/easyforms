<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.3
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\models\forms;

use yii\base\Model;

class CaptchaForm extends Model
{
    /**
     * @var string
     */
    public $captcha;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['captcha', 'required'],
            ['captcha', 'captcha', 'captchaAction'=>'/user/captcha'],
        ];
    }
}