<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $themeModel app\models\search\ThemeSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="theme-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($themeModel, 'id') ?>

    <?= $form->field($themeModel, 'name') ?>

    <?= $form->field($themeModel, 'description') ?>

    <?= $form->field($themeModel, 'color') ?>

    <?= $form->field($themeModel, 'css') ?>

    <?php // echo $form->field($themeModel, 'created_at') ?>

    <?php // echo $form->field($themeModel, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
