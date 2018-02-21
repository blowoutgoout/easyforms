<?php

use yii\web\View;
use yii\helpers\Url;
use app\bundles\FormBuilderBundle;

/* @var $this yii\web\View */
/* @var $model app\models\Template */

FormBuilderBundle::register($this);

// PHP options required by main-built.js
$options = array(
    "libUrl" => Url::to('@web/static_files/js/form.builder/lib/'),
    "i18nUrl" => Url::to(['ajax/builder-phrases']),
    "componentsUrl" => Url::to(['ajax/builder-components']),
    "initPoint" => Url::to(['ajax/init-template']),
    "endPoint" => Url::to(['ajax/create-template']),
    "reCaptchaSiteKey" => Yii::$app->settings->get("app.reCaptchaSiteKey"),
    "afterSave" => 'redirect', // Or 'showMessage'
    "redirectTo" => Url::to(['/templates']),
    "_csrf" => Yii::$app->request->getCsrfToken(),
);

// Pass php options to javascript
$this->registerJs("var options = ".json_encode($options).";", View::POS_BEGIN, 'builder-options');

$this->title = Yii::t('app', 'Create Template');

?>

<div class="form-create">
    <div class="row">
        <!-- Widgets -->
        <div class="col-md-4 sidebar-outer">
            <div class="sidebar">
                <div class="panel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified" id="formtabs" role="tablist">
                        <!-- Tab nav -->
                    </ul>
                    <form id="widgets">
                        <fieldset>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- Tabs of widgets go here -->
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <!-- / Widgets -->

        <!-- Building Form. -->
        <div class="col-md-8">
            <!-- Alert. -->
            <div class="alert alert-warning alert-dismissable fade" style="display: none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong><?= Yii::t("app", "Tip") ?>:</strong>
                <?= Yii::t(
                    "app",
                    "Just Drag the Fields on center to start building your template. It's fast, easy & fun."
                ) ?>
            </div>
            <!-- / Alert. -->
            <div id="canvas">
                <form id="my-form">
                </form>
            </div>
            <div id="messages">
                <div data-alerts="alerts"
                     data-titles="{'warning': '<em><?= Yii::t("app", "Warning!") ?></em>'}"
                     data-ids="myid" data-fade="2000"></div>
            </div>
            <div id="actions">
                <input id="formId" type="hidden" value="">
                <button type="button" class="btn btn-default saveForm" id="saveForm">
                    <span class="glyphicon glyphicon-ok"></span> <?= Yii::t("app", "Save Template") ?></button>
            </div>
        </div>
        <!-- / Building Form. -->

        <!-- .modal -->
        <div class="modal fade" id="saved">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                title="<?= Yii::t('app', 'Create another template') ?>">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?= Yii::t("app", "Great! Your template is saved.") ?></h4>
                    </div>
                    <div class="modal-body">
                        <p><?= Yii::t("app", "What do you want to do now?") ?></p>
                        <div class="list-group">
                            <a href="<?= $url = Url::to(['templates/update']); ?>" id="toUpdate"
                               class="list-group-item">
                                <h4 class="list-group-item-heading"><?= Yii::t("app", "It's Ok.") ?></h4>
                                <p class="list-group-item-text">
                                    <?= Yii::t("app", "I still want to edit this template.") ?></p></a>
                            <a href="<?= $url = Url::to(['templates/settings']); ?>" id="toSettings"
                               class="list-group-item">
                                <h4 class="list-group-item-heading">
                                    <?= Yii::t("app", "Let’s go to Template Settings.") ?></h4>
                                <p class="list-group-item-text">
                                    <?= Yii::t("app", "I need to setup the template category and description.") ?>
                                </p></a>
                            <a href="<?= $url = Url::to(['/templates']); ?>" class="list-group-item">
                                <h4 class="list-group-item-heading">
                                    <?= Yii::t("app", "I finished! Take me back to the Template Manager.") ?></h4>
                                <p class="list-group-item-text">
                                    <?= Yii::t("app", "I want to to promote my template.") ?></p></a>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>
</div>
