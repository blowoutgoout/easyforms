<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.2
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Form;

class RestrictedForm extends Model
{
    public $password;

    public function rules()
    {
        return [
            [['password'], 'required', 'message' => Yii::t('app', 'Please enter your password.')],
            ['password', 'validatePassword'],
        ];
    }
    public function validatePassword()
    {
        $formID = (integer) Yii::$app->request->get('id');

        $formModel = Form::findOne($formID);

        if (is_null($formModel) || $formModel->password !== $this->password) {
            $this->addError('password', Yii::t('app', 'Incorrect password.'));
        }
    }
}