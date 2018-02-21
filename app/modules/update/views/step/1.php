<?php

use yii\bootstrap\Html;
use app\helpers\Language;

$this->title = Yii::t('update', 'Choose language');

// Languages array
$languages = Language::supportedLanguages();

?>

<div class="row">
    <div class="col-sm-4">
        <ul class="list-group">
            <li class="list-group-item list-group-item-current"><?= Yii::t('update', 'Choose language') ?></li>
            <li class="list-group-item"><?= Yii::t('update', 'Requirements') ?></li>
            <li class="list-group-item"><?= Yii::t('update', 'Update app') ?></li>
            <li class="list-group-item"><?= Yii::t('update', 'Finished') ?></li>
        </ul>
    </div>
    <div class="col-sm-8 form-wrapper">
        <?= Html::tag('h4', Yii::t('update', 'Choose language'), ['class' => 'step-title']) ?>
        <?= Html::beginForm('', 'post', ['class' => 'form-vertical']) ?>
        <div class="form-group">
            <?php // Html::label(Yii::t('update', 'Choose language'), 'language', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('language', Yii::$app->language, $languages, ['class'=>'form-control']) ?>
        </div>
        <div class="form-action">
            <?= Html::submitButton(Yii::t('update', 'Save and continue'), ['class'=>'btn btn-primary']) ?>
        </div>
        <?= Html::endForm() ?>
    </div>
</div>