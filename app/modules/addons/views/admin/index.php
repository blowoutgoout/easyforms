<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\components\widgets\ActionBar;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\addons\models\AddonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('addon', 'Add-ons');
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    [
        'class' => '\kartik\grid\CheckboxColumn',
        'headerOptions' => ['class'=>'kartik-sheet-style'],
        'rowSelectedClass' => GridView::TYPE_WARNING,
    ],
    [
        'attribute'=> 'name',
        'format' => 'raw',
        'value' => function ($model) {
            if ($model->installed && $model->status) {
                return Html::a(Html::encode($model->name), ['/addons/' . $model->id]);
            }
            return $model->name;
        },
    ],
    [
        'attribute'=>'version',
        'value'=> 'version',
    ],
    [
        'class'=>'kartik\grid\BooleanColumn',
        'attribute'=>'installed',
        'trueIcon'=>'<span class="glyphicon glyphicon-ok text-success"></span>',
        'falseIcon'=>'<span class="glyphicon glyphicon-remove text-danger"></span>',
        'vAlign'=>'middle',
    ],
    [
        'class'=>'kartik\grid\BooleanColumn',
        'attribute'=>'status',
        'trueIcon'=>'<span class="glyphicon glyphicon-ok text-success"></span>',
        'falseIcon'=>'<span class="glyphicon glyphicon-remove text-danger"></span>',
        'vAlign'=>'middle',
    ],
    [
        'attribute'=>'description',
        'value'=> 'description',
    ],
];

?>
<div class="addons-index">
    <div class="row">
        <div class="col-md-12">
            <?= GridView::widget([
                'id' => 'addons-grid',
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'resizableColumns' => false,
                'pjax' => false,
                'export' => false,
                'responsive' => true,
                'bordered' => false,
                'striped' => true,
                'panelTemplate' => '<div class="panel {type}">
                    {panelHeading}
                    {panelBefore}
                    {items}
                    <div style="text-align: center">{pager}</div>
                </div>',
                'panel' => [
                    'type'=>GridView::TYPE_INFO,
                    'heading'=> Yii::t('addon', 'Add-ons') .' <small class="panel-subtitle hidden-xs">'.
                        Yii::t('addon', 'Extend and Expand the functionality of your forms').'</small>',
                    'footer'=>false,
                    'before'=> (!empty(Yii::$app->user) &&
                        Yii::$app->user->can("admin")) ? // Visible only for admin user
                        ActionBar::widget([
                            'grid' => 'addons-grid',
                            'templates' => [
                                '{refresh}' => ['class' => 'col-xs-6 col-md-8'],
                                '{bulk-actions}' => ['class' => 'col-xs-6 col-md-2 col-md-offset-2'],
                            ],
                            'bulkActionsItems' => [
                                Yii::t('addon', 'Update Status') => [
                                    'status-active' => Yii::t('addon', 'Active'),
                                    'status-inactive' => Yii::t('addon', 'Inactive'),
                                ],
                                Yii::t('addon', 'General') => [
                                    'install' => Yii::t('addon', 'Install'),
                                    'uninstall' => Yii::t('addon', 'Uninstall'),
                                ],
                            ],
                            'bulkActionsOptions' => [
                                'options' => [
                                    'status-active' => [
                                        'url' => Url::toRoute(['update-status', 'status' => 1]),
                                    ],
                                    'status-inactive' => [
                                        'url' => Url::toRoute(['update-status', 'status' => 0]),
                                    ],
                                    'install' => [
                                        'url' => Url::toRoute(['install']),
                                    ],
                                    'uninstall' => [
                                        'url' => Url::toRoute(['uninstall']),
                                        'data-confirm' => Yii::t(
                                            'addon',
                                            'Are you sure you want to uninstall these add-ons? All data related to each item will be deleted. This action cannot be undone.'
                                        ),
                                    ],
                                ],
                                'class' => 'form-control',
                            ],
                            'elements' => [
                                'refresh' =>
                                    Html::a(
                                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-refresh']).' '.
                                        Yii::t('addon', 'Refresh'),
                                        ['refresh'],
                                        ['class' => 'btn btn-primary']
                                    ) .
                                    Html::a(
                                        Html::tag('span', '', [
                                            'class' => 'glyphicon glyphicon-question-sign',
                                            'style' => 'font-size: 18px; color: #6e8292; vertical-align: -3px',
                                        ]),
                                        false,
                                        [
                                            'data-toggle' => 'tooltip',
                                            'data-placement'=> 'top',
                                            'title' => Yii::t(
                                                'addon',
                                                'Use the “Refresh” button to see new Add-ons, after upload or delete add-on’s files.'
                                            ),
                                            'class' => 'text hidden-xs hidden-sm'
                                        ]
                                    ),
                            ],
                            'class' => 'form-control',
                        ]) : null,
                ],
                'toolbar' => false
            ]); ?>
        </div>
    </div>
</div>
<?php
$js = <<< 'SCRIPT'

$(function () {
    $("[data-toggle='tooltip']").tooltip();
});;

SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs($js);