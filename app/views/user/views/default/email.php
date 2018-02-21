<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\UserToken $userToken
 */

$this->title = Yii::t('app', 'Change e-mail address');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-management">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="glyphicon glyphicon-envelope" style="margin-right: 5px;"></i>
                <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'id' => 'change-email-form',
                'enableAjaxValidation' => true,
            ]); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?php if (Yii::$app->getModule("user")->useEmail) : ?>
                        <?= $form->field($user, 'email') ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?php if ($userToken->data !== null) : ?>
                            <p class="small"><?= Yii::t(
                                'app',
                                "Pending email confirmation: [ {newEmail} ]",
                                ["newEmail" => $userToken->data]
                            ) ?>
                            </p>
                            <p class="small"><?= Html::a(Yii::t("app", "Resend"), ["/user/resend-change"]) ?> /
                                <?= Html::a(Yii::t("app", "Cancel"), ["/user/cancel"]) ?></p>
                        <?php elseif (Yii::$app->getModule("user")->emailConfirmation) : ?>
                            <p class="small"><?= Yii::t('app', 'Changing your email requires email confirmation') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($user, 'currentPassword')->passwordInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group" style="text-align: right; margin-top: 10px">
                        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>