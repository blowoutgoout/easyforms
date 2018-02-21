<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\User $user
 * @var bool $success
 * @var bool $invalidKey
 */

$this->title = Yii::t('app', 'Reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-reset">

    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="form-wrapper">
                <?php if (!empty($success)) : ?>

                    <div class="well">
                        <p class="text-success"><?= Yii::t("app", "Your Password has been reset.") ?></p>
                        <p class="text-success"><?= Html::a(Yii::t("app", "Log in here"), ["/user/login"]) ?></p>
                    </div>

                <?php elseif (!empty($invalidKey)) : ?>

                    <div class="well">
                        <p class="text-danger"><?= Yii::t("app", "Invalid key") ?></p>
                    </div>

                <?php else : ?>

                    <?php $form = ActiveForm::begin([
                        'id' => 'reset-form',
                    ]); ?>

                    <?= Html::tag('legend', Html::encode($this->title)) ?>

                    <?= $form->field($user, 'newPassword', [
                        'inputOptions' => [
                            'placeholder' => $user->getAttributeLabel('newPassword'),
                            'class' => 'form-control',
                        ],
                    ])->label(false)->passwordInput() ?>
                    <?= $form->field($user, 'newPasswordConfirm', [
                        'inputOptions' => [
                            'placeholder' => $user->getAttributeLabel('newPasswordConfirm'),
                            'class' => 'form-control',
                        ],
                    ])->label(false)->passwordInput() ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t("app", "Update my password"), ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>