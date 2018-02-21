<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Template */
/* @var $form yii\widgets\ActiveForm */
/* @var $categories app\models\TemplateCategory[] */
/* @var $users array [id => username] of user models */

?>

<div class="template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
        'data' => $categories,
        'options' => ['placeholder' => Yii::t('app', 'Select a category...')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label(Yii::t('app', 'Category')); ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

    <?php if (Yii::$app->user->can('admin') && !$model->isNewRecord): ?>
        <?= $form->field($model, 'created_by')->widget(Select2::classname(), [
            'data' => $users,
            'options' => ['placeholder' => 'Select a username ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'promoted')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>