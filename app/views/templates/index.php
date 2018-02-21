<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use Carbon\Carbon;
use app\models\TemplateCategory;
use app\components\widgets\ActionBar;
use app\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Templates');
$this->params['breadcrumbs'][] = $this->title;

Carbon::setLocale(substr(Yii::$app->language, 0, 2)); // eg. en-US to en

$options = array(
    'currentPage' => Url::toRoute(['index']), // Used by filters
);

// Pass php options to javascript
$this->registerJs("var options = ".json_encode($options).";", View::POS_BEGIN, 'form-options');
?>
<div class="template-index">

    <?= GridView::widget([
        'id' => 'template-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizableColumns' => false,
        'pjax' => false,
        'export' => false,
        'responsive' => true,
        'bordered' => false,
        'striped' => true,
        'panelTemplate' => Html::tag('div', '{panelHeading}{panelBefore}{items}{panelFooter}', [
            'class' => 'panel {type}'
        ]),
        'panelFooterTemplate' => '<div class="kv-panel-pager">{pager}</div>{footer}<div class="clearfix"></div>',
        'panel'=>[
            'type'=>GridView::TYPE_INFO,
            'heading'=> Yii::t('app', 'Templates').' <small class="panel-subtitle hidden-xs">'.
                Yii::t('app', 'Looks & feels amazing on any device').'</small>',
            'before'=> Yii::$app->user->can('admin') ?
                // Action Bar For Administrators
                ActionBar::widget([
                    'grid' => 'template-grid',
                    'templates' => [
                        '{create}' => ['class' => 'col-xs-6 col-md-8'],
                        '{filters}' => ['class' => 'col-xs-2 col-md-2 no-padding'],
                        '{bulk-actions}' => ['class' => 'col-xs-4 col-md-2'],
                    ],
                    'elements' => [
                        'create' =>
                            Html::a(
                                '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app', 'Create Template'),
                                ['create'],
                                ['class' => 'btn btn-primary']
                            ) . ' ' .
                            Html::a(
                                Yii::t('app', 'Templates by Categories'),
                                ['/categories'],
                                [
                                    'data-toggle' => 'tooltip',
                                    'data-placement'=> 'top',
                                    'title' => Yii::t('app', 'Templates organized by Categories'),
                                    'class' => 'text hidden-xs hidden-sm'
                                ]
                            ),
                        'filters' => SwitchInput::widget(
                            [
                                'name'=>'filters',
                                'type' => SwitchInput::CHECKBOX,
                                'pluginOptions' => [
                                    'size' => 'mini',
                                    'animate' => false,
                                    'labelText' => Yii::t('app', 'Filter'),
                                ],
                                'pluginEvents' => [
                                    "switchChange.bootstrapSwitch" => "function(event, state) {
                                            if (state) {
                                                $('.filters').fadeIn()
                                                localStorage.setItem('gridView.filters', 1);
                                            } else {
                                                $('.filters').fadeOut()
                                                localStorage.setItem('gridView.filters', 0);
                                                window.location = options.currentPage;
                                            }
                                        }",
                                ],
                                'containerOptions' => ['style' => 'margin-top: 6px; text-align: right'],
                            ]
                        ),
                    ],
                    'bulkActionsItems' => [
                        Yii::t('app', 'Update Promotion') => [
                            'promoted' => Yii::t('app', 'Promoted'),
                            'non-promoted' => Yii::t('app', 'Non-Promoted'),
                        ],
                        Yii::t('app', 'General') => ['general-delete' => Yii::t('app', 'Delete')],
                    ],
                    'bulkActionsOptions' => [
                        'options' => [
                            'promoted' => [
                                'url' => Url::toRoute(['update-promotion', 'promoted' => 1]),
                            ],
                            'non-promoted' => [
                                'url' => Url::toRoute(['update-promotion', 'promoted' => 0]),
                            ],
                            'general-delete' => [
                                'url' => Url::toRoute('delete-multiple'),
                                'data-confirm' => Yii::t('app', 'Are you sure you want to delete these templates? All data related to each item will be deleted. This action cannot be undone.'),
                            ],
                        ],
                        'class' => 'form-control',
                    ],
                    'class' => 'form-control',
                ]) :
                // Action Bar For Advanced Users
                ActionBar::widget([
                    'grid' => 'template-grid',
                    'templates' => [
                        '{create}' => ['class' => 'col-xs-9 col-md-9'],
                        '{filters}' => ['class' => 'col-xs-3 col-md-3'],
                    ],
                    'elements' => [
                        'create' =>
                            Html::a(
                                '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app', 'Create Template'),
                                ['create'],
                                ['class' => 'btn btn-primary']
                            ),
                        'filters' => SwitchInput::widget(
                            [
                                'name'=>'filters',
                                'type' => SwitchInput::CHECKBOX,
                                'pluginOptions' => [
                                    'size' => 'mini',
                                    'animate' => false,
                                    'labelText' => Yii::t('app', 'Filter'),
                                ],
                                'pluginEvents' => [
                                    "switchChange.bootstrapSwitch" => "function(event, state) {
                                            if (state) {
                                                $('.filters').fadeIn()
                                                localStorage.setItem('gridView.filters', 1);
                                            } else {
                                                $('.filters').fadeOut()
                                                localStorage.setItem('gridView.filters', 0);
                                                window.location = options.currentPage;
                                            }
                                        }",
                                ],
                                'containerOptions' => ['style' => 'margin-top: 6px; text-align: right'],
                            ]
                        ),
                    ],
                ]),
        ],
        'toolbar' => false,
        'columns' => [
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'headerOptions' => ['class'=>'kartik-sheet-style'],
                'rowSelectedClass' => GridView::TYPE_WARNING,
            ],
            [
                'attribute'=> 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    $name = Html::encode($model->name);
                    if (Yii::$app->user->canAccessToTemplate($model->id)) {
                        return Html::a($name, ['templates/view', 'id' => $model->id]);
                    }
                    return $name;
                },
            ],
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function ($model) {
                    if (isset($model->category, $model->category->name)) {
                        return Html::encode($model->category->name);
                    }
                    return null;
                },
                'label' => Yii::t('app', 'Category'),
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'category_id',
                    ArrayHelper::map(
                        TemplateCategory::find()->asArray()->all(),
                        'id',
                        'name'
                    ),
                    ['class'=>'form-control', 'prompt' => '']
                ),
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'promoted',
                'trueIcon'=>'<span class="glyphicon glyphicon-star text-success"></span>',
                'falseIcon'=>'<span class="glyphicon glyphicon-star-empty text-default"></span>',
                'vAlign'=>'middle',
            ],
            [
                'attribute' => 'author',
                'value' => 'author.username',
                'label' => Yii::t('app', 'Created by'),
            ],
            [
                'attribute'=> 'updated_at',
                'value' => function ($model) {
                    return Carbon::createFromTimestampUTC($model->updated_at)->diffForHumans();
                },
                'label' => Yii::t('app', 'Updated'),
                'filterType'=> \kartik\grid\GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'presetDropdown' => true,
                    'convertFormat'=>true,
                    'pluginOptions' => [
                        'locale'=>[
                            'format'=>'Y-m-d h:i A',
                            'separator'=>' - ',
                        ],
                        'opens'=>'left'
                    ] ,
                    'pluginEvents' => [
                        "apply.daterangepicker" => "function() { apply_filter('updated_at') }",
                    ]
                ],
            ],
            ['class' => 'kartik\grid\ActionColumn',
                'dropdown'=>true,
                'dropdownButton' => ['class'=>'btn btn-primary'],
                'dropdownOptions' => ['class' => 'pull-right'],
                'buttons' => [
                    //update button
                    'update' => function ($url) {
                        $options = array_merge([
                            'title' => Yii::t('app', 'Update'),
                            'aria-label' => Yii::t('app', 'Update'),
                            'data-pjax' => '0',
                        ], []);
                        return '<li>'.Html::a('<span class="glyphicon glyphicon-pencil"></span> '.
                            Yii::t('app', 'Update'), $url, $options).'</li>';
                    },
                    //settings button
                    'settings' => function ($url) {
                        return '<li>'.Html::a(
                            '<span class="glyphicon glyphicon-cogwheel"> </span> '. Yii::t('app', 'Settings'),
                            $url,
                            ['title' => Yii::t('app', 'Settings')]
                        ) .'</li>';
                    },
                    //create form button
                    'createForm' => function ($url) {
                        return '<li>'.Html::a(
                            '<span class="glyphicon glyphicon-plus"> </span> '. Yii::t('app', 'Create Form'),
                            $url,
                            ['title' => Yii::t('app', 'Create Form')]
                        ) .'</li>';
                    },
                    //view button
                    'view' => function ($url) {
                        $options = array_merge([
                            'title' => Yii::t('app', 'View Record'),
                            'aria-label' => Yii::t('app', 'View Record'),
                            'data-pjax' => '0',
                        ], []);
                        return '<li>'.Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span> ' . Yii::t('app', 'View Record'),
                            $url,
                            $options
                        ).'</li>';
                    },
                    //delete button
                    'delete' => function ($url) {
                        $options = array_merge([
                            'title' => Yii::t('app', 'Delete'),
                            'aria-label' => Yii::t('app', 'Delete'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to delete this template? All data related to this item will be deleted. This action cannot be undone.'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ], []);
                        return '<li>'.Html::a(
                            '<span class="glyphicon glyphicon-bin"></span> ' . Yii::t('app', 'Delete'),
                            $url,
                            $options
                        ).'</li>';
                    },
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'update') {
                        $url = Url::to(['update', 'id' => $model->id]);
                        return $url;
                    } elseif ($action === "settings") {
                        $url = Url::to(['settings', 'id' => $model->id]);
                        return $url;
                    } elseif ($action === "createForm") {
                        $url = Url::to(['form/create', 'template' => $model->slug]);
                        return $url;
                    } elseif ($action === "view") {
                        $url = Url::to(['view', 'id' => $model->id]);
                        return $url;
                    } elseif ($action === "delete") {
                        $url = Url::to(['delete', 'id' => $model->id]);
                        return $url;
                    }
                    return '';
                },
                'visibleButtons' => [
                    //update button
                    'update' => function ($model, $key, $index) {
                        return Yii::$app->user->canAccessToTemplate($model->id);
                    },
                    //settings button
                    'settings' => function ($model, $key, $index) {
                        return Yii::$app->user->canAccessToTemplate($model->id);
                    },
                    //create form button
                    'createForm' => function ($model, $key, $index) {
                        return Yii::$app->user->can('edit_own_content');
                    },
                    //view button
                    'view' => function ($model, $key, $index) {
                        return Yii::$app->user->canAccessToTemplate($model->id);
                    },
                    //delete button
                    'delete' => function ($model, $key, $index) {
                        return Yii::$app->user->canAccessToTemplate($model->id);
                    },
                ],
                'template' => '{update} {settings} {createForm} {view} {delete}',
            ],
        ],
    ]); ?>

</div>
<?php
$js = <<< 'SCRIPT'

$(function () {
    // Tooltips
    $("[data-toggle='tooltip']").tooltip();
    // Filters
    var state = localStorage.getItem('gridView.filters');
    if (typeof state !== undefined && state == 1) {
        $('input[name="filters"]').bootstrapSwitch('state', true);
    } else {
        $('input[name="filters"]').bootstrapSwitch('state', false);
    }
});

SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs($js);