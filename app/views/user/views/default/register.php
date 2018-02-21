<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\Module $module
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\User $profile
 * @var app\models\forms\CaptchaForm $captchaForm
 * @var string $userDisplayName
 */

$module = Yii::$app->getModule('user');
$useCaptcha = (bool) Yii::$app->settings->get('app.useCaptcha');

$this->title = Yii::t('app', 'Sign Up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-register">
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="form-wrapper">
                <?php if ($flash = Yii::$app->session->getFlash("Register-success")) : ?>

                    <div class="well">
                        <p class="text-success"><?= $flash ?></p>
                    </div>

                <?php else : ?>

                    <?php $form = ActiveForm::begin([
                        'id' => 'register-form',
                        'enableAjaxValidation' => true,
                    ]); ?>

                    <?= Html::tag('legend', Html::encode($this->title)) ?>

                    <?php if ($module->requireEmail) : ?>
                        <?= $form->field($user, 'email', [
                            'inputOptions' => [
                                'placeholder' => $user->getAttributeLabel('email'),
                                'class' => 'form-control',
                            ]])->label(false) ?>
                    <?php endif; ?>

                    <?php if ($module->requireUsername) : ?>
                        <?= $form->field($user, 'username', [
                            'inputOptions' => [
                                'placeholder' => $user->getAttributeLabel('username'),
                                'class' => 'form-control',
                            ]])->label(false) ?>
                    <?php endif; ?>

                    <?= $form->field($user, 'newPassword', [
                        'inputOptions' => [
                            'placeholder' => $user->getAttributeLabel('newPassword'),
                            'class' => 'form-control',
                        ]])->label(false)->passwordInput() ?>

                    <?php if ($useCaptcha) : ?>
                        <?= $form->field($captchaForm, 'captcha')->widget(Captcha::className(), [
                            'captchaAction' => ['/user/captcha'],
                            'options' => [
                                'class' => 'form-control',
                                'placeholder' => Yii::t('app', 'Captcha'),
                            ]
                        ]) ?>
                    <?php endif; ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Sign Up'), ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                <?php endif; ?>
            </div>
            <div class="sub">
                <?= Yii::t('app', 'Already have an account?') ?>
                <?= Html::a(Yii::t('app', 'Log In'), ["/user/login"]) ?>
            </div>
        </div>
    </div>
</div>