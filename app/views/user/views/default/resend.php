<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\forms\ResendForm $model
 */

$this->title = Yii::t('app', 'Resend');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-resend">
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="form-wrapper">
                <?php if ($flash = Yii::$app->session->getFlash('Resend-success')) : ?>

                    <div class="well">
                        <p class="text-success"><?= $flash ?></p>
                    </div>

                <?php else : ?>

                    <?php $form = ActiveForm::begin([
                        'id' => 'resend-form',
                    ]); ?>

                    <?= Html::tag('legend', Html::encode($this->title)) ?>

                    <?= $form->field($model, 'email', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('email'),
                            'class' => 'form-control',
                        ]])->label(false) ?>
                    <div class="form-group">
                        <?= Html::submitButton(
                            Yii::t('app', 'Resend confirmation link'),
                            ['class' => 'btn btn-primary']
                        ) ?>
                    </div>
                    <?php ActiveForm::end(); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>