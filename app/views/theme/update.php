<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $themeModel app\models\Theme */
/* @var $forms array [id => name] of form models */
/* @var $users array [id => username] of user models */

$this->title = Yii::t('app', 'Update Theme') . ': ' . $themeModel->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Themes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $themeModel->name, 'url' => ['view', 'id' => $themeModel->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="theme-update box box-big box-light">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Update Theme') ?>
            <span class="box-subtitle"><?= Html::encode($themeModel->name) ?></span>
        </h3>
    </div>

    <?= $this->render('_form', [
        'themeModel' => $themeModel,
        'forms' => $forms,
        'users' => $users,
    ]) ?>

</div>
