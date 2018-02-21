<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Dropdown;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use Carbon\Carbon;
use app\components\widgets\ActionBar;
use app\helpers\Language;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\FormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
/* @var $templates array */

$this->title = Yii::t("app", "Forms");
$this->params['breadcrumbs'][] = $this->title;

// Prepare dropdown with templates array
$templateItems = array();

if (count($templates) > 0) {
    // Set data for dropdown widget
    foreach ($templates as $template) {
        $item = [
            'label' => $template['name'],
            'url' => Url::to(['create', 'template' => $template['slug']]),
        ];
        array_push($templateItems, $item);
    }
    $itemDivider = [
        'label' => '<li role="presentation" class="divider"></li>',
        'encode' => false,
    ];
    array_push($templateItems, $itemDivider);
}

// Add link to templates
$itemMoreTeplates = [
    'label' => Yii::t('app', 'More Templates'),
    'url' => Url::to(['/templates']),
];
array_push($templateItems, $itemMoreTeplates);

Carbon::setLocale(substr(Yii::$app->language, 0, 2)); // eg. en-US to en

$options = array(
    'currentPage' => Url::toRoute(['index']), // Used by filters
);

// Pass php options to javascript
$this->registerJs("var options = ".json_encode($options).";", View::POS_BEGIN, 'form-options');
?>
<?php

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
                return Html::a(Html::encode($model->name), ['form/view', 'id' => $model->id]);
            },
        ],
        [
            'attribute'=>'language',
            'value'=> 'languageLabel',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'language',
                Language::supportedLanguages(),
                ['class'=>'form-control', 'prompt' => '']
            ),
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
            'attribute'=>'save',
            'trueIcon'=>'<span class="glyphicon glyphicon-ok text-success"></span>',
            'falseIcon'=>'<span class="glyphicon glyphicon-remove text-danger"></span>',
            'vAlign'=>'middle',
        ],
        [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'honeypot',
            'trueIcon'=>'<span class="glyphicon glyphicon-ok text-success"></span>',
            'falseIcon'=>'<span class="glyphicon glyphicon-remove text-danger"></span>',
            'vAlign'=>'middle',
        ],
        [
            'attribute' => 'author',
            'value' => function ($model) {
                return isset($model->author, $model->author->username) ? Html::encode($model->author->username) : null;
            },
            'label' => Yii::t("app", "Created by")
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
        ['class' => '\kartik\grid\ActionColumn',
            'controller' => 'form',
            // Visible for all users
            'visible' => true,
            'dropdown'=>true,
            'dropdownButton' => ['class'=>'btn btn-primary'],
            'dropdownOptions' => ['class' => 'pull-right'],
            'buttons' => [
                //update button
                'update' => function ($url) {
                    return '<li>'.Html::a(
                        '<span class="glyphicon glyphicon-pencil"> </span> '. Yii::t('app', 'Update'),
                        $url,
                        ['title' => Yii::t('app', 'Update')]
                    ) .'</li>';
                },
                //settings button
                'settings' => function ($url) {
                    return '<li>'.Html::a(
                        '<span class="glyphicon glyphicon-cogwheel"> </span> '. Yii::t('app', 'Settings'),
                        $url,
                        ['title' => Yii::t('app', 'Settings')]
                    ) .'</li>';
                },
                //rule button
                'rules' => function ($url) {
                    return '<li>'.Html::a(
                        '<span class="glyphicon glyphicon-flowchart"> </span> '. Yii::t('app', 'Conditional Rules'),
                        $url,
                        ['title' => Yii::t('app', 'Conditional Rules')]
                    ) .'</li>';
                },
                //preview form button
                'view' => function ($url) {
                    return '<li>'.Html::a(
                            '<span class="glyphicon glyphicon-eye-open"> </span> ' . Yii::t('app', 'View Record'),
                            $url,
                            ['title' => Yii::t('app', 'View Record')]
                        ) .'</li>';
                },
                //copy button
                'copy' => function ($url) {
                    $options = array_merge([
                        'title' => Yii::t('app', 'Copy'),
                        'aria-label' => Yii::t('app', 'Copy'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ], []);
                    return '<li>'.Html::a(
                            '<span class="glyphicon glyphicon-duplicate"> </span> '.
                            Yii::t('app', 'Copy'),
                            $url,
                            $options
                        ).'</li>';
                },
                //share form button
                'share' => function ($url) {
                    return '<li>'.Html::a(
                        '<span class="glyphicon glyphicon-share"> </span> '. Yii::t('app', 'Publish & Share'),
                        $url,
                        ['title' => Yii::t('app', 'Publish & Share')]
                    ) .'</li>';
                },
                //form submissions button
                'submissions' => function ($url) {
                    return '<li>'.Html::a(
                        '<span class="glyphicon glyphicon-send"> </span> '. Yii::t('app', 'Submissions'),
                        $url,
                        ['title' => Yii::t('app', 'Submissions')]
                    ) .'</li>';
                },
                //form report button
                'report' => function ($url) {
                    return '<li>'.Html::a(
                        '<span class="glyphicon glyphicon-pie-chart"> </span> '. Yii::t('app', 'Submissions Report'),
                        $url,
                        ['title' => Yii::t('app', 'Submissions Report')]
                    ) .'</li>';
                },
                //form analytics button
                'analytics' => function ($url) {
                    return '<li>'.Html::a(
                        '<span class="glyphicon glyphicon-charts"> </span> '. Yii::t('app', 'Form Analytics'),
                        $url,
                        ['title' => Yii::t('app', 'Form & Submissions Analytics')]
                    ) .'</li>';
                },
                //reset stats button
                'reset_stats' => function ($url) {
                    $options = array_merge([
                        'title' => Yii::t('app', 'Reset Stats'),
                        'aria-label' => Yii::t('app', 'Reset Stats'),
                        'data-confirm' => Yii::t('app', 'Are you sure you want to delete these stats? All stats related to this item will be deleted. This action cannot be undone.'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ], []);
                    return '<li>'.Html::a(
                        '<span class="glyphicon glyphicon-refresh"> </span> '.
                        Yii::t('app', 'Reset Stats'),
                        $url,
                        $options
                    ).'</li>';
                },
                //delete button
                'delete' => function ($url) {
                    $options = array_merge([
                        'title' => Yii::t('app', 'Delete'),
                        'aria-label' => Yii::t('app', 'Delete'),
                        'data-confirm' => Yii::t('app', 'Are you sure you want to delete this form? All stats, submissions, conditional rules and reports data related to this item will be deleted. This action cannot be undone.'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ], []);
                    return '<li>'.Html::a(
                        '<span class="glyphicon glyphicon-bin"> </span> '.
                        Yii::t('app', 'Delete'),
                        $url,
                        $options
                    ).'</li>';
                },
            ],
            'urlCreator' => function ($action, $model) {
                if ($action === 'update') {
                    $url = Url::to(['form/update', 'id' => $model->id]);
                    return $url;
                } elseif ($action === "settings") {
                    $url = Url::to(['form/settings', 'id' => $model->id]);
                    return $url;
                } elseif ($action === "rules") {
                    $url = Url::to(['form/rules', 'id' => $model->id]);
                    return $url;
                } elseif ($action === "view") {
                    $url = Url::to(['form/view', 'id' => $model->id]);
                    return $url;
                } elseif ($action === "copy") {
                    $url = Url::to(['form/copy', 'id' => $model->id]);
                    return $url;
                } elseif ($action === "share") {
                    $url = Url::to(['form/share', 'id' => $model->id]);
                    return $url;
                } elseif ($action === "submissions") {
                    $url = Url::to(['form/submissions', 'id' => $model->id]);
                    return $url;
                } elseif ($action === "report") {
                    $url = Url::to(['form/report', 'id' => $model->id]);
                    return $url;
                } elseif ($action === "analytics") {
                    $url = Url::to(['form/analytics', 'id' => $model->id]);
                    return $url;
                } elseif ($action === "reset_stats") {
                    $url = Url::to(['form/reset-stats', 'id' => $model->id]);
                    return $url;
                } elseif ($action === "delete") {
                    $url = Url::to(['form/delete', 'id' => $model->id]);
                    return $url;
                }
                return '';
            },
            'template' => Yii::$app->user->can('edit_own_content') ?
                '{update} {settings} {rules} {view} {copy} {share} {submissions} {report} {analytics} {reset_stats} {delete}' :
                '{view} {share} {submissions} {report} {analytics}',
        ],
    ];

?>

<div class="form-index">
    <div class="row">
        <div class="col-md-12">
            <?= GridView::widget([
                'id' => 'form-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumns,
                'resizableColumns' => false,
                'pjax' => false,
                'export' => false,
                'responsive' => true,
                'bordered' => false,
                'striped' => true,
                'panelTemplate' => Yii::$app->user->can('edit_own_content') ?
                    Html::tag('div', '{panelHeading}{panelBefore}{items}{panelFooter}', ['class' => 'panel {type}']) :
                    Html::tag('div', '{panelHeading}{items}{panelFooter}', ['class' => 'panel {type}']),
                'panel' => [
                    'type'=>GridView::TYPE_INFO,
                    'heading'=> Yii::t('app', 'Forms') .' <small class="panel-subtitle hidden-xs">'.
                        Yii::t('app', 'Build any type of online form').'</small>',
                    // Not visible for basic user
                    'before'=>
                        ActionBar::widget([
                            'grid' => 'form-grid',
                            'templates' => [
                                '{create}' => ['class' => 'col-xs-6 col-md-8'],
                                '{filters}' => ['class' => 'col-xs-2 col-md-2 no-padding'],
                                '{bulk-actions}' => ['class' => 'col-xs-4 col-md-2'],
                            ],
                            'elements' => [
                                'create' =>
                                    '<div class="btn-group">' .
                                        Html::a(
                                            '<span class="glyphicon glyphicon-plus"></span> ' .
                                            Yii::t('app', 'Create Form'),
                                            ['create'],
                                            ['class' => 'btn btn-primary']
                                        ) .
                                        '<button type="button" class="btn btn-primary dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>' .
                                        Dropdown::widget(['items' => $templateItems]) .
                                    '</div> ' .
                                    Html::a(Yii::t('app', 'Do you want to customize your forms?'), ['/theme'], [
                                        'data-toggle' => 'tooltip',
                                        'data-placement'=> 'top',
                                        'title' => Yii::t('app', 'No problem at all. With a theme, you can easily add custom CSS styles to your forms, to customize colors, field sizes, backgrounds, fonts, and more.'),
                                        'class' => 'text hidden-xs hidden-sm']),
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
                                Yii::t('app', 'Update Status') => [
                                    'status-active' => Yii::t('app', 'Active'),
                                    'status-inactive' => Yii::t('app', 'Inactive'),
                                ],
                                Yii::t('app', 'General') => ['general-delete' => Yii::t('app', 'Delete')],
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
                                        'data-confirm' => Yii::t('app', 'Are you sure you want to delete these forms? All stats, submissions, conditional rules and reports data related to each item will be deleted. This action cannot be undone.'),
                                    ],
                                ],
                                'class' => 'form-control',
                            ],

                            'class' => 'form-control',
                        ]),
                ],
                'toolbar' => false
            ]); ?>
        </div>
    </div>
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