<?php

use yii\helpers\Url;
use app\helpers\Html;
use app\bundles\SubmissionsBundle;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $formModel app\models\Form */
/* @var $formDataModel app\models\FormData */

SubmissionsBundle::register($this);

$this->title = $formModel->name;

// File fields, used to populate DetailView
$fileFields = $formDataModel->getFileFields();

// All fields, except Buttons and files
$fields = $formDataModel->getFieldsForSubmissions();
$totalFields = count($fields);

// PHP options required by submissions.js
$options = array(
    'fields' => $fields,
    'fileFields' => $fileFields,
    'formName' => Html::encode($formModel->name),
    'hasPrettyUrls' => Yii::$app->urlManager->enablePrettyUrl,
    'endPoint' => Url::to(['submissions/index']),
    'createEndPoint' => Url::to(['submissions/create']),
    'updateEndPoint' => Url::to(['submissions/update']),
    'deleteEndPoint' => Url::to(['submissions/delete']),
    'uploadEndPoint' => Url::to(['submissions/upload', 'id' => $formModel->id]),
    'addCommentEndPoint' => Url::to(['submissions/add-comment', 'id' => $formModel->id]),
    'deleteCommentEndPoint' => Url::to(['submissions/delete-comment', 'id' => $formModel->id]),
    'deleteFileEndPoint' => Url::to(['submissions/delete-file', 'id' => $formModel->id]),
    'updateAllEndPoint' => Url::to(['submissions/updateall']),
    'deleteAllEndPoint' => Url::to(['submissions/deleteall']),
    'csvEndPoint' => Url::to(['/form/export-submissions', 'id' => $formModel->id, 'format' => 'csv']),
    'xlsxEndPoint' => Url::to(['/form/export-submissions', 'id' => $formModel->id, 'format' => 'xlsx']),
    'formID' => $formModel->id,
    'language' => Yii::$app->language,
    'i18n' => [
        'index' => Yii::t('app', 'Index'),
        'submissionDetails' => Yii::t('app', 'Submission Details'),
        'addSubmission' => Yii::t('app', 'Add Submission'),
        'editSubmission' => Yii::t('app', 'Edit Submission'),
        'bulkActions' => Yii::t('app', 'Bulk Actions'),
        'uploadingFile' => Yii::t('app', 'You have files upload pending! Files will be lost!'),
        'errorOnUpdate' => Yii::t('app', 'Sorry, the items haven\'t been updated.'),
        'errorOnDelete' => Yii::t('app', 'Sorry, the items haven\'t been deleted.'),
        'areYouSureDeleteCommentItem' => Yii::t('app', 'Are you sure you want to delete this comment? All data related to this item will be deleted. This action cannot be undone.'),
        'areYouSureDeleteFileItem' => Yii::t('app', 'Are you sure you want to delete this file? All data related to this item will be deleted. This action cannot be undone.'),
        'areYouSureDeleteItem' => Yii::t('app', 'Are you sure you want to delete this submission? All data related to this item will be deleted. This action cannot be undone.'),
        'areYouSureDeleteItems' => Yii::t('app', 'Are you sure you want to delete these submissions? All data related to each item will be deleted. This action cannot be undone.'),
        'today' => Yii::t('app', 'Today'),
        'yesterday' => Yii::t('app', 'Yesterday'),
        'last7Days' => Yii::t('app', 'Last 7 Days'),
        'last30Days' => Yii::t('app', 'Last 30 Days'),
        'thisMonth' => Yii::t('app', 'This Month'),
        'lastMonth' => Yii::t('app', 'Last Month'),
        'customRange' => Yii::t('app', 'Custom Range'),
        'apply' => Yii::t('app', 'Apply'),
        'clear' => Yii::t('app', 'Clear'),
    ]
);

// Pass php options to javascript, and load before form.settings.js
$this->registerJs("var options = ".json_encode($options).";", $this::POS_BEGIN, 'submissions-options');

// Add print css
$this->registerCssFile('@web/static_files/css/print.submission.css', [
    'depends' => [SubmissionsBundle::className()],
    'media' => 'print',
], 'css-print-submission');

?>
<div class="submissions-page">

    <div id="main">
    </div>

    <script type="text/template" id="navTemplate">
        <ul class="breadcrumb breadcrumb-arrow hidden-print">
            <li><?= Html::a(Yii::t('app', 'Dashboard'), ['/dashboard']) ?></li>
            <li><?= Html::a(Yii::t('app', 'Forms'), ['/form']) ?></li>
            <li><?= Html::a(Html::encode($formModel->name), ['form/view', 'id' => $formModel->id]) ?></li>
            {{ if (page !== '<?= Yii::t("app", "Index") ?>') { }}
            <li><?= Html::a(
                    Yii::t("app", "Submissions"),
                    ['form/submissions', 'id' => $formModel->id, '#' => '']
                ) ?></li>
            <li class="active"><span>{{= page }}</span></li>
            {{ } else { }}
            <li class="active"><span><?= Yii::t("app", "Submissions") ?></span></li>
            {{ } }}
        </ul>
    </script>

    <script type="text/template" id="submissionsTemplate">
        <div class="grid-view">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="summary">
                        <?= Yii::t("app", "Showing {min} - {max} of {totalCount}", [
                            "min" => "<b id='min'>{{= range.min }}</b>",
                            "max" => "<b id='max'>{{= range.max }}</b>",
                            "totalCount" => "<b id='total'>{{= totalCount }}</b>"
                        ]) ?> {{ if( totalCount > 1 ) { }} <?= Yii::t('app', 'items') ?>
                        {{ } else { }}
                        <?= Yii::t('app', 'item') ?>{{ } }}.
                        <span id="loading"><?= Yii::t("app", "Loading...") ?></span>
                    </div>
                    <h3 class="panel-title">
                        <?= Html::encode($this->title) ?>
                        <small class="panel-subtitle hidden-xs"><?= Yii::t("app", "Submissions") ?></small></h3>
                </div>
                <div class="panel-subheading"> <!-- panel before -->
                    <div class="widget-action-bar">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <div id="range" class="form-control" style="cursor: pointer">
                                        <i class="glyphicon glyphicon-calendar"></i>&nbsp;
                                        <span id="date-range"></span>
                                        <b class="caret"></b>
                                    </div>
                                    <div class="input-group-btn">
                                        <?= Html::a(
                                            '<span class="glyphicon glyphicon-filter"></span> ' . Yii::t('app', 'Filter'),
                                            ['#'],
                                            ['id' => 'filter-link', 'class' => 'btn btn-primary', 'style' => 'margin-bottom:10px', 'data-start' => '', 'data-end' => '']) ?>
                                        <!-- Single button -->
                                        <button type="button" class="btn btn-primary dropdown-toggle" style="margin-bottom:10px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-download-alt"></span> <?= Yii::t('app', 'Export')?> <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><?= Html::a(
                                                    Yii::t('app', 'Export as CSV'),
                                                    ['/form/export-submissions', 'id' => $formModel->id, 'format' => 'csv'],
                                                    ['id' => 'csv-link']) ?></li>
                                            <li><?= Html::a(
                                                    Yii::t('app', 'Export as MS Excel'),
                                                    ['/form/export-submissions', 'id' => $formModel->id, 'format' => 'xlsx'],
                                                    ['id' => 'xlsx-link']) ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-offset-1">
                                <div class="pull-right">
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group" role="group">
                                            <div class="btn-group" role="group">
                                                <a href="#/add" class="btn btn-primary">
                                                    <span class="glyphicon glyphicon-plus"
                                                          title="<?= Yii::t("app", "Add Submission") ?>"></span></a>
                                                <button type="button" class="btn btn-primary"
                                                        id="refreshBtn" title="<?= Yii::t("app", "Reset") ?>">
                                                    <i class="glyphicon glyphicon-refresh"></i></button>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button"
                                                        class="btn btn-primary btn-for-toggle resizeColumns"
                                                        title="<?= Yii::t("app", "Resize Full") ?>">
                                                    <i class="glyphicon glyphicon-resize-full"></i></button>
                                                <button type="button"
                                                        class="btn btn-primary btn-for-toggle resizeColumns"
                                                        title="<?= Yii::t("app", "Resize Small") ?>">
                                                    <i class="glyphicon glyphicon-resize-small"></i></button>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button"
                                                        class="btn btn-primary dropdown-toggle"
                                                        title="<?= Yii::t("app", "Show / Hide columns") ?>"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    <i class="glyphicon glyphicon-table"></i>
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right showHideColumns"
                                                    role="menu">
                                                    <?php $i = 1; foreach ($fields as $field) : ?>
                                                        <?php ++$i ?>
                                                        <li class="item">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" class="column"
                                                                           data-key="<?= $field['name'] ?>"
                                                                           value="<?= $i ?>" checked="checked">
                                                                    <?= StringHelper::truncateWords($field['label'], 20) ?></label></div></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                        title="<?= Yii::t("app", "Bulk Actions") ?>"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    <i class="glyphicon glyphicon-check"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <li><a href="#" id="markAsRead">
                                                            <i class="glyphicon glyphicon-ok"></i>
                                                            <?= Yii::t("app", "Mark as Read") ?></a></li>
                                                    <li><a href="#" id="deleteSelectedRows">
                                                            <i class="glyphicon glyphicon-bin"></i>
                                                            <?= Yii::t("app", "Delete") ?></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <input type="text" class="form-control searchTxt"
                                                   placeholder="<?= Yii::t("app", "Search") ?>">
                                            <div class="input-group-btn">
                                                <button class="btn btn-primary searchBtn">
                                                    <i class="glyphicon glyphicon-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-list">
                        <thead>
                        <tr>
                            <th class="check"><input id="allRows" name="allRows" type="checkbox"></th>
                            <?php foreach ($fields as $field) : ?>
                                <th title="<?= $field['label'] ?>"><?= $field['label'] ?></th>
                            <?php endforeach; ?>
                            <th class="created_at"><a href="#" id="submitted_at">
                                    <?= Yii::t("app", "Submitted") ?></a></th>
                            <th class="actions"><?= Yii::t("app", "Actions") ?></th>
                        </tr>
                        </thead>
                        <tbody>{{ if( totalCount < 1 ) { }}
                        <tr><td colspan="<?= $totalFields + 3 ?>"><div class="empty">
                                    <?= Yii::t('app', 'No results found.') ?></div></td></tr>{{ } }}
                        </tbody>
                    </table>
                </div>
                <div id="pagination" class="text-center clearfix"></div>
            </div>
        </div>
    </script>

    <script type="text/template" id="submissionTemplate">
        <div class="check table-cell"><input type="checkbox" class="row" data-id="{{= id }}"></div>
        <?php foreach ($fields as $field) { ?>
            <div data-key="<?= $field['name'] ?>" class="view table-cell">{{- data['<?= $field['name'] ?>'] }}</div>
        <?php }; ?>
        <div class="created_at table-cell">{{= created_at }} {{ if ( isNew ) { }} <span class="label label-info">
                <?= Yii::t("app", "New") ?></span> {{ } }}</div>
        <div class="actions table-cell">
            <a href="#" class="view" title="<?= Yii::t("app", "View") ?>">
                <span class="glyphicon glyphicon-eye-open"></span></a>
            <a href="#" class="edit" title="<?= Yii::t("app", "Update") ?>">
                <span class="glyphicon glyphicon-pencil"></span></a>
            <a href="#" class="remove" title="<?= Yii::t("app", "Delete") ?>">
                <span class="glyphicon glyphicon-bin"></span></a>
        </div>
    </script>

    <script type="text/template" id="bulkTemplate">
        <div class="well">
            <h5><?= Yii::t("app", "Do you want to export all your submissions?") ?></h5>
            <p><?= Html::a(
                    '<span class="glyphicon glyphicon-download-alt"></span> ' . Yii::t("app", "Export as CSV"),
                    ['/form/export-submissions', 'id' => $formModel->id],
                    ['class' => 'btn btn-primary']
                ) ?></p>
        </div>
    </script>

    <script type="text/template" id="formTemplate">
        <div class="grid-view">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="pull-right">
                        <div class="summary">{{= subtitle }}.</div>
                    </div>
                    <h3 class="panel-title">{{- form_name }}
                        <small class="panel-subtitle hidden-xs">{{= subtitle }}</small> </h3>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-subheading" style="padding-bottom: 20px">
                    <div class="widget-action-bar">
                        <div class="row">
                            <div class="col-xs-6 col-md-8">
                                <a href="#" class="btn btn-default" onclick="App.Router.back(); return false;">
                                    <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                                    <?= Yii::t("app", "Go Back") ?></a>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="pull-right">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding: 0 20px 20px 20px">
                    <?= Html::removeScriptTags(Html::decode($formDataModel->html)); ?>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="detailTemplate">
        <div class="grid-view">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="pull-right">
                        <div class="summary hidden-xs"><?= Yii::t('app', 'Showing 1 item.') ?></div>
                    </div>
                    <h3 class="panel-title">{{= form_name }} <small class="panel-subtitle hidden-xs">
                            <?= Yii::t("app", "Submission Details") ?></small> </h3>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-subheading hidden-print" style="padding-bottom: 20px">
                    <div class="widget-action-bar">
                        <div class="row">
                            <div class="col-xs-6 col-md-8">
                                <a href="#" class="btn btn-default" onclick="App.Router.back(); return false;">
                                    <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                                    <?= Yii::t("app", "Go Back") ?></a>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="pull-right">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info edit"
                                                title="<?= Yii::t("app", "Edit Submission Details") ?>">
                                            <i class="glyphicon glyphicon-pencil"></i></button>
                                        <a href="javascript:window.print()" class="btn btn-info"
                                           title="<?= Yii::t("app", "Print Submission Details") ?>">
                                            <i class="glyphicon glyphicon-print"></i>
                                        </a>
                                    </div>
                                    <button type="button" class="btn btn-danger remove"
                                            title="<?= Yii::t("app", "Delete Submission") ?>">
                                        <i class="glyphicon glyphicon-bin"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding: 0 20px">
                    <div class="col-sm-8">
                        <table class="table table-bordered table-striped table-detail">
                            <tbody>
                            <tr class="info">
                                <th colspan="2">
                                    <?= Yii::t("app", "Submission Details") ?>
                                    <small class="pull-right emptyFields" style="font-weight: normal; font-size: 70%"><input type="checkbox" id="showEmptyFields" {{= showEmptyFields }} /> <?= Yii::t('app', 'Empty Fields') ?></small>
                                </th>
                            </tr>
                            <tr>
                                <th><?= Yii::t("app", "Submission") ?> #</th>
                                <td>{{= id }}</td>
                            </tr>

                            <?php foreach ($fields as $field) : ?>
                                {{ if (showEmptyFields || (data['<?= $field['name'] ?>'] && (!_.isArray(data['<?= $field['name'] ?>']) || !_.isEmpty(data['<?= $field['name'] ?>'][0]) ))) { }}
                                <tr>
                                    <th><?= $field['label'] ?></th>
                                    <td data-key="<?= $field['name'] ?>">{{- data['<?= $field['name'] ?>'] }}</td>
                                </tr>
                                {{ } }}
                            <?php endforeach; ?>

                            <?php if (count($fileFields) > 0) { ?>
                                {{ if( _.size(files) > 0 ) { }}
                                <tr class="info">
                                    <th colspan="2"><?= Yii::t("app", "Files Information") ?></th>
                                </tr>
                                {{ _.each(files, function(file, i) { }}
                                <tr data-file="{{= file.id }}">
                                    <th>{{= file.label }}</th>
                                    <td>
                                        <a target="_blank" href="{{= file.link }}" title="<?= Yii::t("app", "Download File") ?>">{{= file.originalName }}</a>
                                        ({{= file.extension }}, {{= file.sizeWithUnit }}) <a class="removeFile" data-id="{{= file.id }}"><span class="glyphicon glyphicon-remove"> </span></a>
                                    </td>
                                </tr>
                                {{ }); }}
                                {{ } }}
                            <?php }; ?>

                            <tr class="info">
                                <th colspan="2"><?= Yii::t("app", "Sender Information") ?></th>
                            </tr>
                            {{ if(sender.country) { }}
                            <tr>
                                <th><?= Yii::t("app", "Country") ?></th>
                                <td>{{= sender.country }}</td>
                            </tr>
                            {{ } }}
                            {{ if(sender.city) { }}
                            <tr>
                                <th><?= Yii::t("app", "City") ?></th>
                                <td>{{= sender.city }}</td>
                            </tr>
                            {{ } }}
                            {{ if(sender.longitude && sender.latitude) { }}
                            <tr class="hidden-print">
                                <th><?= Yii::t("app", "Location") ?></th>
                                <td><div id="map"></div></td>
                            </tr>
                            {{ } }}
                            <tr>
                                <th><?= Yii::t("app", "IP Address") ?></th>
                                <td>{{= ip }}</td>
                            </tr>
                            <tr>
                                <th><?= Yii::t("app", "Browser") ?></th>
                                <td>{{= sender.user_agent }}</td>
                            </tr>
                            <tr class="info">
                                <th colspan="2"><?= Yii::t("app", "Additional Information") ?></th>
                            </tr>
                            {{ if(author) { }}
                            <tr>
                                <th><?= Yii::t("app", "Submitted by") ?></th>
                                <td>{{- author }}</td>
                            </tr>
                            {{ } }}
                            <tr>
                                <th><?= Yii::t("app", "Submitted") ?></th>
                                <td>{{= created_at }}</td>
                            </tr>
                            {{ if(lastEditor) { }}
                            <tr>
                                <th><?= Yii::t("app", "Updated by") ?></th>
                                <td>{{- lastEditor }}</td>
                            </tr>
                            <tr>
                                <th><?= Yii::t("app", "Updated") ?></th>
                                <td>{{= updated_at }}</td>
                            </tr>
                            {{ } }}
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-4">
                        <div class="commentBox">
                            <div class="titleBox"><?= Yii::t('app', 'Comments') ?></div>
                            <div class="actionBox">
                                <ul id="commentList" class="commentList">
                                    {{ _.each(comments, function(comment, i) { }}
                                    <li class="comment{{ if(i%2) { }} alt{{ } }}" data-comment-id="{{= comment.id }}">
                                        <div class="commentText">
                                            <p><span class="author">{{= comment.authorName }}</span> - <span class="date sub-text">{{= comment.submitted }}</span> <a href="#" class="deleteComment" data-id="{{= comment.id }}" title="<?= Yii::t('app', 'Delete Comment') ?>"><span class="glyphicon glyphicon-remove"> </span></a></p>
                                            <p class="">{{= comment.content }}</p>
                                        </div>
                                    </li>
                                    {{ }); }}
                                </ul>
                                <form role="form" class="clearfix">
                                    <div class="form-group">
                                        <textarea id="commentContent" class="form-control" placeholder="<?= Yii::t('app', 'Your comments')?>"></textarea>
                                    </div>
                                    <div class="">
                                        <button id="addComment" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> <?= Yii::t('app', 'Save Comment') ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="paginationTemplate">
        {{ if( pageCount > 1 ) { }}
        <nav>
            <ul class="pagination">
                <li {{ if( currentPage == 1 ) { }} class="disabled" {{ } }} >
                    <a href="#" aria-label="<?= Yii::t("app", "First") ?>" class="first"><?= Yii::t("app", "First") ?></a>
                </li>
                <li {{ if( !prev ) { }} class="disabled" {{ } }} >
                    <a href="#" aria-label="<?= Yii::t("app", "Previous") ?>" class="prev">
                        <?= Yii::t("app", "Previous") ?></a>
                </li>
                <li {{ if( !next ) { }} class="disabled" {{ } }} >
                    <a href="#" aria-label="<?= Yii::t("app", "Next") ?>" class="next"><?= Yii::t("app", "Next") ?></a>
                </li>
                <li {{ if( currentPage == pageCount ) { }} class="disabled" {{ } }} >
                    <a href="#" aria-label="<?= Yii::t("app", "Last") ?>" class="last"><?= Yii::t("app", "Last") ?></a>
                </li>
            </ul>
        </nav>
        {{ } }}
    </script>

</div>