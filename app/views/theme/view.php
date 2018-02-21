<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use Carbon\Carbon;

/* @var $this yii\web\View */
/* @var $themeModel app\models\Theme */

$this->title = isset($themeModel->name) ? $themeModel->name : $themeModel->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Themes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Carbon::setLocale(substr(Yii::$app->language, 0, 2)); // eg. en-US to en
?>
<div class="theme-view box box-big box-light">

    <div class="pull-right" style="margin-top: -5px">
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ', ['update', 'id' => $themeModel->id], [
            'title' => Yii::t('app', 'Update Theme'),
            'class' => 'btn btn-sm btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-bin"></span> ', ['delete', 'id' => $themeModel->id], [
            'title' => Yii::t('app', 'Delete Theme'),
            'class' => 'btn btn-sm btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this theme? All data related to this item will be deleted. This action cannot be undone.'),
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Theme') ?>
            <span class="box-subtitle"><?= Html::encode($this->title) ?></span>
        </h3>
    </div>

    <?= DetailView::widget([
        'model' => $themeModel,
        'condensed'=>false,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'enableEditMode'=> false,
        'hideIfEmpty'=>true,
        'options' => [
            'class' => 'kv-view-mode', // Fix hideIfEmpty if enableEditMode is false
        ],
        'attributes' => [
            'id',
            'name',
            'description',
            [
                'attribute'=>'color',
                'format'=>'raw',
                'value'=>"<span class='badge' style='background-color: {$themeModel->color}'>&nbsp;</span> <code>" .
                    $themeModel->color . '</code>',
                'type'=>DetailView::INPUT_COLOR,
            ],
            //'css:ntext',
            [
                'attribute' => 'author',
                'value' => $themeModel->author->username,
                'label' => Yii::t('app', 'Created by'),
            ],
            [
                'attribute' => 'created_at',
                'value' => isset($themeModel->created_at) ? Carbon::createFromTimestampUTC($themeModel->created_at)
                    ->diffForHumans() : null,
                'label' => Yii::t('app', 'Created'),
            ],
            [
                'attribute' => 'lastEditor',
                'value' => $themeModel->lastEditor->username,
                'label' => Yii::t('app', 'Last Editor'),
            ],
            [
                'attribute' => 'updated_at',
                'value' => isset($themeModel->updated_at) ? Carbon::createFromTimestampUTC($themeModel->updated_at)
                    ->diffForHumans() : null,
                'label' => Yii::t('app', 'Last updated'),
            ],
        ],
    ]) ?>

</div>
