<?php

use app\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\builder\FormGrid;
use Carbon\Carbon;
use app\bundles\WysiwygBundle;

/* @var $this yii\web\View */
/* @var $formModel app\models\Form */
/* @var $formDataModel app\models\FormData */
/* @var $formConfirmationModel app\models\FormConfirmation */
/* @var $formEmailModel app\models\FormEmail */
/* @var $formUIModel app\models\FormUI */
/* @var $themes array [id => name] of theme models */
/* @var $form \kartik\form\ActiveForm */

WysiwygBundle::register($this);

$this->title = $formModel->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $formModel->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');

/*
 * Data For From
 * If email fields, add to emails data for fill from field (of Form Email)
 */

// Emails of the application
$adminEmail = Yii::$app->settings->get("app.adminEmail");
$supportEmail = Yii::$app->settings->get("app.supportEmail");
$noreplyEmail = Yii::$app->settings->get("app.noreplyEmail");

// Emails to show in the form
$emails = array(
    'Emails' => [
        $adminEmail => $adminEmail,
        $supportEmail => $supportEmail,
        $noreplyEmail => $noreplyEmail,
    ]
);

// Email fields of the form
$emailLabels = $formDataModel->getEmailLabels();

$emailFields = array(
    Yii::t('app', 'Email Fields') => $emailLabels,
);

// If the form has email fields, add to config form
if (sizeof($emailLabels) > 0) {
    $emails = array_merge($emails, $emailFields);
}

// PHP options required by editor.js
$options = array(
    "endPoint" => Url::to(['app/preview']),
    "formID" => $formModel->id,
    "iframe" => "formI",
    "iHeight" => 250,
    "language" => Carbon::setLocale(substr(Yii::$app->language, 0, 2)), // eg. en-US to en
);

// Pass php options to javascript, and load before form.settings.js
$this->registerJs("var options = ".json_encode($options).";", $this::POS_BEGIN, 'editor-options');

// Load form.settings.js after AppBundle
$this->registerJsFile('@web/static_files/js/form.settings.min.js', ['depends' => WysiwygBundle::className()]);

?>
<div class="form-config-page">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?> <small><?= Yii::t('app', 'Settings') ?></small></h1>
    </div>

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]); ?>

    <div class="panel">
        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active">
                    <a href="#form_settings" aria-controls="form_settings" role="tab" data-toggle="tab">
                        <?= Yii::t('app', 'Form Settings') ?></a></li>
                <li role="presentation">
                    <a href="#form_confirmation_settings" aria-controls="form_confirmation_settings"
                       role="tab" data-toggle="tab"><?= Yii::t('app', 'Confirmation Settings') ?></a></li>
                <li role="presentation">
                    <a href="#form_notification_settings" aria-controls="form_notification_settings" role="tab"
                       data-toggle="tab">
                        <?= Yii::t('app', 'Notification Settings') ?></a></li>
                <li role="presentation">
                    <a href="#form_theme_settings" aria-controls="form_theme_settings" role="tab" data-toggle="tab">
                        <?= Yii::t('app', 'UI Settings') ?></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="form_settings">
                    <?php echo FormGrid::widget([
                        'model' => $formModel,
                        'form' => $form,
                        'autoGenerateColumns' => true,
                        'columnSize' => Form::SIZE_TINY,
                        'rows' => [
                            [
                                'contentBefore'=> Html::tag(
                                    'legend',
                                    Yii::t('app', 'Form Settings'),
                                    ['class' => 'text-primary']
                                ),
                                'attributes' => [
                                    'name' => [
                                        'type'=>Form::INPUT_TEXT,
                                        'options'=>['placeholder'=>Yii::t("app", "Enter the form name..."),]],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'status' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=> Yii::t("app", "Disables the form at any time."),
                                    ],
                                    'language'=>[
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\select2\Select2',
                                        'hint'=> Yii::t(
                                            "app",
                                            "This language will be used to display your form messages."
                                        ),
                                        'options'=>[
                                            'data'=> \app\helpers\Language::supportedLanguages()
                                        ],
                                    ],
                                ]
                            ],
                            [
                                'attributes' => [
                                    'message' => [
                                        'type'=>Form::INPUT_TEXTAREA,
                                        'hint'=> Yii::t(
                                            "app",
                                            "Message displayed to the user when the form is disabled."
                                        ),
                                        'options'=>['placeholder'=> Yii::t("app", "Enter message...")]],
                                ],
                            ],
                            [
                                'contentBefore'=> Html::tag(
                                    'legend',
                                    Yii::t('app', 'Form Activity & Limits'),
                                    ['class' => 'text-primary']
                                ),
                                'columns'=>12,
                                'autoGenerateColumns'=>false, // override columns setting
                                'attributes' => [
                                    'total_limit' => [
                                        'type'  => Form::INPUT_RAW,
                                        'value' => $form->field($formModel, 'total_limit')->radioButtonGroup(
                                            [
                                                $formModel::ON => Yii::t('app', 'Yes'),
                                                $formModel::OFF => Yii::t('app', 'No'),
                                            ],
                                            [
                                                'itemOptions' => ['labelOptions' => ['class' => 'btn btn-primary']],
                                                'style' => 'display:block; margin-bottom:15px; overflow:hidden',
                                            ]
                                        ),
                                        'columnOptions'=>['colspan'=>6],
                                    ],
                                    'ip_limit' => [
                                        'type'  => Form::INPUT_RAW,
                                        'value' => $form->field($formModel, 'ip_limit')->radioButtonGroup(
                                            [
                                                $formModel::ON => Yii::t('app', 'Yes'),
                                                $formModel::OFF => Yii::t('app', 'No'),
                                            ],
                                            [
                                                'itemOptions' => ['labelOptions' => ['class' => 'btn btn-primary']],
                                                'style' => 'display:block; margin-bottom:15px; overflow:hidden',
                                            ]
                                        ),
                                        'columnOptions'=>['colspan'=>6],
                                    ],
                                ]
                            ],
                            [
                                'columns'=>12,
                                'autoGenerateColumns'=>false, // override columns setting
                                'attributes' => [
                                    'total_limit_number' => ['type'=>Form::INPUT_TEXT,
                                        'options'=>[
                                            'placeholder'=>Yii::t("app", "Enter the total number..."),
                                        ],
                                        'columnOptions'=>['colspan'=>3],
                                    ],
                                    'total_limit_period' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\select2\Select2',
                                        'options'=>[
                                            'data'=> $formModel->getTimePeriods(),
                                            'pluginOptions' => [
                                                'placeholder' => Yii::t('app', 'Select time period'),
                                                'allowClear' => true
                                            ],
                                        ],
                                        'columnOptions'=>['colspan'=>3],
                                    ],
                                    'ip_limit_number' => ['type'=>Form::INPUT_TEXT,
                                        'options'=>[
                                            'placeholder'=>Yii::t("app", "Enter the max number..."),
                                        ],
                                        'columnOptions'=>['colspan'=>3],
                                    ],
                                    'ip_limit_period' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\select2\Select2',
                                        'options'=>[
                                            'data'=> $formModel->getTimePeriods(),
                                            'pluginOptions' => [
                                                'placeholder' => Yii::t('app', 'Select time period'),
                                                'allowClear' => true
                                            ],
                                        ],
                                        'columnOptions'=>['colspan'=>3],
                                    ],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'schedule' => [
                                        'type'  => Form::INPUT_RAW,
                                        'value' => $form->field($formModel, 'schedule')->radioButtonGroup(
                                            [
                                                $formModel::ON => Yii::t('app', 'Yes'),
                                                $formModel::OFF => Yii::t('app', 'No'),
                                            ],
                                            [
                                                'itemOptions' => ['labelOptions' => ['class' => 'btn btn-primary']],
                                                'style' => 'display:block; margin-bottom:15px; overflow:hidden',
                                            ]
                                        ),
                                    ],
                                ]
                            ],
                            [
                                'columns'=>12,
                                'autoGenerateColumns'=>false, // override columns setting
                                'attributes' => [
                                    'schedule_start_date' => ['type'=>Form::INPUT_WIDGET,
                                        'widgetClass' => \kartik\datecontrol\DateControl::className(),
                                        'options' => [
                                            'type'=>\kartik\datecontrol\DateControl::FORMAT_DATETIME,
                                            'displayTimezone'=> Yii::$app->timeZone,
                                            'options' => [
                                                'options' => [
                                                    'placeholder' => 'Select start date...',
                                                ],
                                            ],
                                        ],
                                        'columnOptions'=>['colspan'=>3],
                                    ],
                                    'schedule_end_date' => ['type'=>Form::INPUT_WIDGET,
                                        'widgetClass' => \kartik\datecontrol\DateControl::className(),
                                        'options' => [
                                            'type'=>\kartik\datecontrol\DateControl::FORMAT_DATETIME,
                                            'displayTimezone'=> Yii::$app->timeZone,
                                            'options' => [
                                                'options' => [
                                                    'placeholder' => 'Select end date...',
                                                ],
                                            ],
                                        ],
                                        'columnOptions'=>['colspan'=>3],
                                    ],
                                ]
                            ],
                            [
                                'contentBefore'=> Html::tag(
                                    'legend',
                                    Yii::t('app', 'Form Security'),
                                    ['class' => 'text-primary']
                                ),
                                'columns'=>12,
                                'autoGenerateColumns'=>false, // override columns setting
                                'attributes' => [
                                    'use_password' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=> Yii::t("app", "Enable password protection."),
                                        'options' => [
                                            'pluginEvents' => [
                                                "switchChange.bootstrapSwitch" => "function(event, state) {
                                                        if (state) {
                                                            $('.field-form-password').show()
                                                        } else {
                                                            $('.field-form-password').hide()
                                                        }
                                                    }",
                                            ],
                                        ],
                                        'columnOptions'=>['colspan'=>3],
                                    ],
                                    'honeypot' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=> Yii::t("app", "Adds a hidden text field to filter spam."),
                                        'columnOptions'=>['colspan'=>3],
                                    ],
                                    'authorized_urls' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=> Yii::t("app", "Restrict access to authorized websites."),
                                        'options' => [
                                            'pluginEvents' => [
                                                "switchChange.bootstrapSwitch" => "function(event, state) {
                                                        if (state) {
                                                            $('.field-form-urls').show()
                                                        } else {
                                                            $('.field-form-urls').hide()
                                                        }
                                                    }",
                                            ],
                                        ],
                                        'columnOptions'=>['colspan'=>3],
                                    ],
                                    'novalidate' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=> Yii::t("app", "Disable client side validation."),
                                        'columnOptions'=>['colspan'=>3],
                                    ],
                                ],
                            ],
                            [
                                'columns'=>12,
                                'autoGenerateColumns'=>false, // override columns setting
                                'attributes' => [
                                    'password' => [
                                        'type'=>Form::INPUT_TEXT,
                                        'options'=>['placeholder'=>Yii::t("app", "Enter the form password...")],
                                        'columnOptions'=>['colspan'=>6],
                                        'hint'=> Yii::t("app", "Only those who know the password can see your form."),
                                    ],
                                    'urls' => [
                                        'type'=>Form::INPUT_TEXT,
                                        'options'=>['placeholder'=>Yii::t("app", "example.com, example.net")],
                                        'columnOptions'=>['colspan'=>6],
                                        'hint'=> Yii::t("app", "Please, enter a comma separated list of valid domain names."),
                                    ],
                                ],
                            ],
                            [
                                'contentBefore'=> Html::tag(
                                    'legend',
                                    Yii::t('app', 'Other Options'),
                                    ['class' => 'text-primary']
                                ),
                                'attributes' => [
                                    'save' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=> Yii::t("app", "Saves all form submissions in the database."),
                                    ],
                                    'analytics' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=> Yii::t("app", "Enable Form Tracking."),
                                    ],
                                    'autocomplete' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=> Yii::t("app", "Enable the browser's autocomplete."),
                                    ],
                                    'resume' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=> Yii::t("app", "Auto save incomplete form filling and resume later."),
                                    ],
                                ],
                            ],
                        ]]);
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="form_confirmation_settings">
                    <?php echo FormGrid::widget([
                        'model' => $formConfirmationModel,
                        'form' => $form,
                        'autoGenerateColumns' => true,
                        'rows' => [
                            [
                                'contentBefore'=> Html::tag(
                                    'legend',
                                    Yii::t('app', 'Confirmation Settings'),
                                    ['class' => 'text-primary']
                                ),
                                'attributes' => [
                                    'type' => [
                                        'type'  => Form::INPUT_RAW,
                                        'value' => $form->field($formConfirmationModel, 'type')->radioButtonGroup(
                                            $formConfirmationModel->getTypes(),
                                            [
                                                'itemOptions' => ['labelOptions' => ['class' => 'btn btn-primary']],
                                                'style' => 'display:block; margin-bottom:15px; overflow:hidden',
                                            ]
                                        ),
                                    ],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'message' => ['type'=>Form::INPUT_TEXTAREA, 'options'=>[
                                        'placeholder'=> Yii::t("app", "Your Confirmation Message...")]],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'url' => ['type'=>Form::INPUT_TEXT, 'options'=>[
                                        'placeholder'=> Yii::t("app", "Enter URL...")]],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'send_email' => [
                                        'type'  => Form::INPUT_RAW,
                                        'value' => $form->field($formConfirmationModel, 'send_email')->radioButtonGroup(
                                            [
                                                $formConfirmationModel::CONFIRM_BY_EMAIL_ENABLE => Yii::t('app', 'Yes'),
                                                $formConfirmationModel::CONFIRM_BY_EMAIL_DISABLE => Yii::t('app', 'No'),
                                            ],
                                            [
                                                'itemOptions' => ['labelOptions' => ['class' => 'btn btn-primary']],
                                                'style' => 'display:block; margin-bottom:15px; overflow:hidden',
                                            ]
                                        ),
                                    ],
                                ]
                            ],
                            [
                                'attributes' => [
                                    'mail_to' => ['type'=>Form::INPUT_WIDGET, 'widgetClass'=>'\kartik\select2\Select2',
                                        'hint' => Yii::t(
                                            "app",
                                            "Your form must have an email field to use this feature."
                                        ),
                                        'options'=>[
                                            'data'=> $emailFields,
                                            'options' => [
                                                'placeholder' => Yii::t("app", "Select an e-mail field..."),
                                                'multiple' => true,
                                            ],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ]],
                                    'mail_from' => ['type'=>Form::INPUT_TEXT, 'options'=>[
                                        'placeholder' => Yii::t("app", "Enter your e-mail address...")]],
                                    'mail_from_name' => [
                                        'type'=>Form::INPUT_TEXT,
                                        'options'=>['placeholder' => Yii::t("app", "Enter your name or company...")]
                                    ],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'mail_subject' => [
                                        'type'=>Form::INPUT_TEXT,
                                        'options'=>['placeholder'=> Yii::t("app", "Enter subject...")]
                                    ],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'mail_message' => [
                                        'type'=>Form::INPUT_TEXTAREA,
                                        'hint'=> Html::tag('small', Yii::t("app", "Allowed HTML Tags:") . ' ' .
                                            Html::encode(
                                                implode(' ', Html::allowedHtml5Tags())
                                            )),
                                        'options'=>[
                                            'placeholder'=> Yii::t("app", "Your Confirmation Message by E-Mail...")
                                        ]
                                    ],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'mail_receipt_copy' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=>''
                                    ],
                                ],
                            ],
                        ]]);
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="form_notification_settings">
                    <?php echo FormGrid::widget([
                        'model' => $formEmailModel,
                        'form' => $form,
                        'autoGenerateColumns' => true,
                        'rows' => [
                            [
                                'contentBefore'=> Html::tag(
                                    'legend',
                                    Yii::t('app', 'Email Notification Settings'),
                                    ['class' => 'text-primary']
                                ),
                                'attributes' => [
                                    'subject' => [
                                        'type'=>Form::INPUT_TEXT,
                                        'options'=>[
                                            'placeholder'=> Yii::t("app", "Enter subject..."),
                                        ]
                                    ],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'to' => [
                                        'type'=>Form::INPUT_TEXT,
                                        'hint'=> Yii::t(
                                            "app",
                                            "Notifications wil be e-mailed to this address, e.g. 'admin@example.com'."
                                        ),
                                        'options'=> ['placeholder'=> Yii::t("app", "Enter e-mail address...")]],
                                    'from' => ['type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\select2\Select2', 'options'=>['data'=> $emails]],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'cc' => ['type'=>Form::INPUT_TEXT,
                                        'options'=>['placeholder'=> Yii::t("app", "Enter e-mail address...")]],
                                    'bcc' => ['type'=>Form::INPUT_TEXT,
                                        'options'=>['placeholder'=> Yii::t("app", "Enter e-mail address...")]],
                                ],
                            ],
                            [
                                'autoGenerateColumns'=>false, // override columns setting
                                'columns'=>12,
                                'attributes' => [
                                    'type' => [
                                        'columnOptions'=>['colspan'=>6],
                                        'type'=>Form::INPUT_RAW,
                                        'value'=>$form->field($formEmailModel, 'type')->radioButtonGroup(
                                            [
                                                $formEmailModel::TYPE_ALL => Yii::t("app", "All Data"),
                                                $formEmailModel::TYPE_LINK => Yii::t("app", "Only Link"),
                                                $formEmailModel::TYPE_MESSAGE => Yii::t("app", "Custom Message"),
                                            ],
                                            [
                                                'itemOptions' => ['labelOptions' => ['class' => 'btn btn-primary']],
                                                'style' => 'display:block; margin-bottom:15px; overflow:hidden',]
                                        )->hint(Yii::t(
                                            "app",
                                            "This email may contain all submitted data, a link to saved data or a custom message."
                                        )),
                                    ],
                                    'attach' => [
                                        'columnOptions'=>['colspan'=>3],
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=>''
                                    ],
                                    'plain_text' => [
                                        'columnOptions'=>['colspan'=>3],
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\switchinput\SwitchInput',
                                        'hint'=>''
                                    ],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'message' => ['type'=>Form::INPUT_TEXTAREA, 'hint'=>
                                        Html::tag('small', Yii::t("app", "Allowed HTML Tags:") . ' ' .
                                            Html::encode(
                                                implode(' ', Html::allowedHtml5Tags())
                                            )),
                                        'options'=>[
                                            'placeholder'=> Yii::t("app", "Enter your custom message...")
                                        ]],
                                ],
                            ],
                        ]
                    ]);
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="form_theme_settings">
                    <?php echo FormGrid::widget([
                        'model' => $formUIModel,
                        'form' => $form,
                        'autoGenerateColumns' => true,
                        'columnSize' => Form::SIZE_TINY,
                        'rows' => [[
                            'contentBefore'=>
                                Html::tag('legend', Yii::t('app', 'UI Settings'), ['class' => 'text-primary']),
                                'attributes' => [
                                    'js_file' => [
                                        'type'=>Form::INPUT_TEXT,
                                        'hint'=> Yii::t(
                                            "app",
                                            "This custom javascript file will be loaded each time the form is being displayed."
                                        ),
                                        'options'=>[
                                            'placeholder'=> Yii::t("app", "Enter URL...")
                                        ]
                                    ],
                                ],
                            ],
                            [
                                'attributes' => [
                                    'theme_id' => [
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\select2\Select2',
                                        'label' => Yii::t('app', 'Select a Theme'),
                                        'hint' => Yii::t("app", "Select the theme that fits best to your form."),
                                        'options'=>[
                                            'data'=> $themes,
                                            'pluginOptions' => [
                                                'placeholder' => Yii::t('app', 'Select a Theme'),
                                                'allowClear' => true
                                            ],
                                            'pluginEvents' => [
                                                "select2:select" => "previewSelected",
                                                "select2:unselect" => "previewUnselected"
                                            ],
                                        ]
                                    ],
                                ],
                            ],
                        ]]);
                    ?>

                    <!-- Preview panel -->
                    <div class="panel panel-default" id="preview-container" style="display:none;">
                        <div class="panel-heading clearfix">
                            <div class="summary pull-left"><strong><?= Yii::t("app", "Preview") ?></strong></div>
                            <div class="pull-right">
                                <a id="resizeFull" class="toogleButton" href="javascript:void(0)">
                                    <i class="glyphicon glyphicon-resize-full"></i>
                                </a>
                                <a id="resizeSmall" class="toogleButton" style="display: none"
                                   href="javascript:void(0)">
                                    <i class="glyphicon glyphicon-resize-small"></i>
                                </a>
                            </div>
                        </div>
                        <div class="panel-body" id="preview">
                        </div>
                    </div>

                </div>
                <div class="form-group" style="text-align: right; margin-top: 30px">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> ' . Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>

<?php
// Hide Form fields by default
$css = <<<CSS
.field-form-schedule_start_date, .field-form-schedule_end_date,
.field-form-password, .field-form-urls,
.field-form-total_limit_number, .field-form-total_limit_period,
.field-form-ip_limit_number, .field-form-ip_limit_period,
.field-formconfirmation-message, .field-formconfirmation-url,
.field-formemail-message {
  display:none;
}
.trumbowyg-box,
.trumbowyg-editor {
  min-height: 180px;
  margin: inherit; 
}
.trumbowyg-editor,
.trumbowyg-textarea {
  min-height: 180px;
}
CSS;
$this->registerCss($css);
