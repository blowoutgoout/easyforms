<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\UserToken $userToken
 */

$this->title = Yii::t('app', 'Account');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-account">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="glyphicon glyphicon-user" style="margin-right: 5px;"></i> <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'id' => 'account-form',
                'options' => ['enctype' => 'multipart/form-data'],
                'enableAjaxValidation' => true,
            ]); ?>

            <?php if (Yii::$app->getModule("user")->useEmail) : ?>
                <?= $form->field($user, 'email') ?>
            <?php endif; ?>

            <?php if ($userToken->data !== null) : ?>
                <p class="small">
                    <?= Yii::t('app', "Pending email confirmation: [ {newEmail} ]", ["newEmail" => $userToken->data]) ?>
                </p>
                <p class="small">
                    <?= Html::a(Yii::t("app", "Resend"), ["/user/resend-change"]) ?> /
                    <?= Html::a(Yii::t("app", "Cancel"), ["/user/cancel"]) ?></p>
            <?php elseif (Yii::$app->getModule("user")->emailConfirmation) : ?>
                <p class="small"><?= Yii::t('app', 'Changing your email requires email confirmation') ?></p>
            <?php endif; ?>

            <?php if (Yii::$app->getModule("user")->useUsername) : ?>
                <?= $form->field($user, 'username') ?>
            <?php endif; ?>

            <?= $form->field($user, 'newPassword')->passwordInput() ?>

            <hr/>

            <?= $form->field($user, 'currentPassword')->passwordInput() ?>

            <div class="form-action">
                <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary pull-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?php foreach ($user->userAuths as $userAuth) : ?>
                    <p><?php Yii::t('app', 'Linked Social Account') ?>: <?= ucfirst($userAuth->provider) ?> / <?= $userAuth->provider_id ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>