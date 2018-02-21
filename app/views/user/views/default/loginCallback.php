<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\user\Module $module
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Profile $profile
 * @var app\modules\user\models\UserToken $userToken
 */

$module = Yii::$app->getModule('user');

$this->title = Yii::t('app', 'Sign Up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-login-callback">
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="form-wrapper">
                <?php if (!$userToken) : ?>
                    <div class="well">
                        <p class="text-danger"><?= Yii::t("app", "Invalid Token") ?></p>
                    </div>
                <?php else : ?>
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                    ]); ?>
                    <?= $form->field($user, 'email', [
                        'inputOptions' => [
                            'placeholder' => $user->getAttributeLabel('email'),
                            'class' => 'form-control',
                        ]])->textInput(['disabled' => true])->label(false) ?>
                    <?php if ($module->useUsername) : ?>
                        <?= $form->field($user, 'username', [
                            'inputOptions' => [
                                'placeholder' => $user->getAttributeLabel('username'),
                                'class' => 'form-control',
                            ]])->label(false) ?>
                    <?php endif; ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Sign Up'), ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
