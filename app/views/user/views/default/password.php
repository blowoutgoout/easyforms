<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\User $user
 */

$this->title = Yii::t('app', 'Change password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-management">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="glyphicon glyphicon-keys" style="margin-right: 5px;"></i> <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'id' => 'change-password-form',
                'enableAjaxValidation' => true,
            ]); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($user, 'newPassword')->passwordInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($user, 'newPasswordConfirm')
                        ->passwordInput()->label(Yii::t('app', 'Repeat New Password')) ?>
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