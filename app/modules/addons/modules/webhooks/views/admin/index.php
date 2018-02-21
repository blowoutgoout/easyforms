<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use kartik\grid\GridView;
use app\components\widgets\ActionBar;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\addons\modules\webhooks\models\WebhookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('webhooks', 'Webhooks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('webhooks', 'Add-ons'), 'url' => ['/addons']];
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    [
        'class' => '\kartik\grid\CheckboxColumn',
        'headerOptions' => ['class'=>'kartik-sheet-style'],
        'rowSelectedClass' => GridView::TYPE_WARNING,
    ],
    [
        'attribute'=> 'form',
        'format' => 'raw',
        'value' => function ($model) {
            return isset($model->form, $model->form->name) ?
                Html::a(Html::encode($model->form->name), ['view', 'id' => $model->id ]) :
                null;
        },
    ],
    [
        'attribute' => 'url',
        'value' => function ($model) {
            if (isset($model->url)) {
                return StringHelper::truncate(Html::encode($model->url), 90);
            }
            return null;
        }
    ],
    [
        'class'=>'kartik\grid\BooleanColumn',
        'attribute'=>'status',
        'trueIcon'=>'<span class="glyphicon glyphicon-ok text-success"></span>',
        'falseIcon'=>'<span class="glyphicon glyphicon-remove text-danger"></span>',
        'vAlign'=>'middle',
    ],
    [
        'class'=>'kartik\grid\BooleanColumn',
        'attribute'=>'json',
        'trueIcon'=>'<span class="glyphicon glyphicon-ok text-success"></span>',
        'falseIcon'=>'<span class="glyphicon glyphicon-remove text-danger"></span>',
        'vAlign'=>'middle',
    ],
    ['class' => 'kartik\grid\ActionColumn',
        'dropdown'=>true,
        'dropdownButton' => ['class'=>'btn btn-primary'],
        'dropdownOptions' => ['class' => 'pull-right'],
        'buttons' => [
            //view button
            'view' => function ($url) {
                $options = array_merge([
                    'title' => Yii::t('webhooks', 'View Record'),
                    'aria-label' => Yii::t('webhooks', 'View Record'),
                    'data-pjax' => '0',
                ], []);
                return '<li>'.Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' .
                    Yii::t('webhooks', 'View Record'), $url, $options).'</li>';
            },
            //update button
            'update' => function ($url) {
                $options = array_merge([
                    'title' => Yii::t('webhooks', 'Update'),
                    'aria-label' => Yii::t('webhooks', 'Update'),
                    'data-pjax' => '0',
                ], []);
                return '<li>'.Html::a('<span class="glyphicon glyphicon-pencil"></span> ' .
                    Yii::t('webhooks', 'Update'), $url, $options).'</li>';
            },
            //delete button
            'delete' => function ($url) {
                $options = array_merge([
                    'title' => Yii::t('webhooks', 'Delete'),
                    'aria-label' => Yii::t('webhooks', 'Delete'),
                    'data-confirm' => Yii::t(
                        'webhooks',
                        'Are you sure you want to delete this webhook? All data related to this item will be deleted. This action cannot be undone.'
                    ),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ], []);
                return '<li>'.Html::a('<span class="glyphicon glyphicon-bin"></span> ' .
                    Yii::t('webhooks', 'Delete'), $url, $options).'</li>';
            },
        ],
    ],
];

?>
    <div class="google-analytics-index">
        <div class="row">
            <div class="col-md-12">
                <?= GridView::widget([
                    'id' => 'webhooks-grid',
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
                        'heading'=> Yii::t('webhooks', 'Webhooks') . ' <small class="panel-subtitle hidden-xs">'.
                            Yii::t('webhooks', 'Send notifications to another server').'</small>',
                        'footer'=>false,
                        // Visible only for admin user
                        'before'=> (!empty(Yii::$app->user) && Yii::$app->user->can("admin")) ?
                            ActionBar::widget([
                                'grid' => 'webhooks-grid',
                                'templates' => [
                                    '{create}' => ['class' => 'col-xs-6 col-md-8'],
                                    '{bulk-actions}' => ['class' => 'col-xs-6 col-md-2 col-md-offset-2'],
                                ],
                                'elements' => [
                                    'create' =>
                                        Html::a(
                                            '<span class="glyphicon glyphicon-plus"></span> ' .
                                            Yii::t('webhooks', 'Create a WebHook'),
                                            ['create'],
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
                                                    'webhooks',
                                                    "A WebHook is just a push notification from us to another server every time someone submits a form."
                                                ),
                                                'class' => 'text hidden-xs hidden-sm'
                                            ]
                                        ),
                                ],
                                'bulkActionsItems' => [
                                    Yii::t('webhooks', 'Update Status') => [
                                        'status-active' => Yii::t('webhooks', 'Active'),
                                        'status-inactive' => Yii::t('webhooks', 'Inactive'),
                                    ],
                                    'General' => ['general-delete' => 'Delete'],
                                ],
                                'bulkActionsOptions' => [
                                    'options' => [
                                        'status-active' => [
                                            'url' => Url::toRoute(['update-status', 'status' => 1]),
                                        ],
                                        'status-inactive' => [
                                            'url' => Url::toRoute(['update-status', 'status' => 0]),
                                        ],
                                        'general-delete' => [
                                            'url' => Url::toRoute('delete-multiple'),
                                            'data-confirm' => Yii::t(
                                                'webhooks',
                                                'Are you sure you want to delete these webhooks? All data related to each item will be deleted. This action cannot be undone.'
                                            ),
                                        ],
                                    ],
                                    'class' => 'form-control',
                                ],
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