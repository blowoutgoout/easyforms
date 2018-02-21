<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var app\modules\user\Module $module
 * @var app\modules\user\models\forms\LoginEmailForm $loginEmailForm
 * @var app\models\forms\CaptchaForm $captchaForm
 */

$module = Yii::$app->getModule('user');
$useCaptcha = (bool) Yii::$app->settings->get('app.useCaptcha');

$this->title = Yii::t('app', 'Sign In without password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-login-email">
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="form-wrapper">
                <?php if ($flash = Yii::$app->session->getFlash("Login-success")) : ?>
                    <div class="well">
                        <p class="text-success"><?= $flash ?></p>
                        <p class="text-danger" style="margin-top: 15px">
                            <?= Yii::t('app', 'The link expires in {loginExpireTime}', [
                                'loginExpireTime' => $module->loginExpireTime
                            ]) ?>.
                        </p>
                    </div>
                <?php else : ?>
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    <?= Html::tag('legend', Html::encode($this->title)) ?>
                    <?= $form->field($loginEmailForm, 'email', [
                        'inputOptions' => [
                            'placeholder' => $loginEmailForm->getAttributeLabel('email'),
                            'class' => 'form-control',
                        ]])->label(false) ?>
                    <?php if ($useCaptcha) : ?>
                        <?= $form->field($captchaForm, 'captcha')->widget(Captcha::className(), [
                            'captchaAction' => ['/user/captcha'],
                            'options' => [
                                'class' => 'form-control',
                                'placeholder' => Yii::t('app', 'Captcha'),
                            ]
                        ]) ?>
                    <?php endif; ?>
                    <?= Html::submitButton(Yii::t('app', 'Log In'), ['class' => 'btn btn-primary']) ?>
                    <?php ActiveForm::end(); ?>
                <?php endif; ?>
            </div>
            <div class="sub">
            </div>
        </div>
</div>
