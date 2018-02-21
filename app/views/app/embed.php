<?php

use yii\web\View;
use yii\web\JqueryAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use app\helpers\UrlHelper;
use app\helpers\Pager;
use app\helpers\Honeypot;

/* @var $this yii\web\View */
/* @var $formModel app\models\Form */
/* @var $formDataModel app\models\FormData */
/* @var $formConfirmationModel app\models\FormConfirmation */
/* @var $formRuleModels app\models\FormRule[] */
/* @var $showTheme boolean Show or hide theme css */
/* @var $customJS boolean Load or Not Custom Javascript File */
/* @var $record boolean Enable / Disable record stats dynamically */

$this->title = $formModel->name;

/** @var $rules array Conditions and Actions of active rules */
$rules = [];

foreach ($formRuleModels as $formRuleModel) {
    $rule = [
        'conditions' => $formRuleModel['conditions'],
        'actions' => $formRuleModel['actions'],
        'opposite' => (boolean) $formRuleModel['opposite'],
    ];
    array_push($rules, $rule);
}

// Base URL without schema
$baseUrl = UrlHelper::removeScheme(Url::home(true));

// Get default values in order to pre-populate the form
$defaultValues = Yii::$app->request->getQueryParam('defaultValues');

// PHP options required by embed.js
$options = array(
    "id" => $formModel->id,
    "app" => UrlHelper::removeScheme(Url::to(['/app'], true)),
    "tracker" => $baseUrl . "/static_files/js/form.tracker.js",
    "name" => "#form-app",
    "actionUrl" => Url::to(['app/a', 'id' => $formModel->id], true),
    "validationUrl" => Url::to(['app/check', 'id' => $formModel->id], true),
    "_csrf" => Yii::$app->request->getCsrfToken(),
    "resume" => $formModel->resume,
    "autocomplete" => $formModel->autocomplete,
    "novalidate" => $formModel->novalidate,
    "analytics" => $formModel->analytics && $record,
    "confirmationType" => $formConfirmationModel->type,
    "confirmationMessage" => false,
    "confirmationUrl" => $formConfirmationModel->url,
    "showOnlyMessage" => $formConfirmationModel::CONFIRM_WITH_ONLY_MESSAGE,
    "redirectToUrl" => $formConfirmationModel::CONFIRM_WITH_REDIRECTION,
    "rules" => $rules,
    "fieldIds" => $formDataModel->getFieldIds(),
    "submitted" => false,
    "runOppositeActions" => true,
    "skips" => [],
    "defaultValues" => !empty($defaultValues) ? Json::decode(Json::htmlEncode($defaultValues)) : false,
    "i18n" => [
        'complete' => Yii::t('app', 'Complete'),
        'unexpectedError' => Yii::t('app', 'An unexpected error has occurred. Please retry later.'),
    ]
);

// Pass php options to javascript
$this->registerJs("var options = ".json_encode($options).";", View::POS_BEGIN, 'form-options');

// Load reCAPTCHA JS Api
// Only if Form has reCaptcha component and was not passed in this session
if ($formModel->recaptcha === $formModel::RECAPTCHA_ACTIVE && !Yii::$app->session['reCaptcha']) {
    $this->registerJsFile('https://www.google.com/recaptcha/api.js', ['position' => View::POS_HEAD]);
    $this->registerCss(".g-recaptcha { height: 78px; }");
}

// Pager
$pager = new Pager(Html::decode($formDataModel->html));

// Utilities required for javascript files
$this->registerJsFile('@web/static_files/js/form.utils.min.js', ['depends' => JqueryAsset::className()]);

// If form has multiple pages
if ($pager->getNumberOfPages() > 1) {
    // Animations
    $this->registerJsFile('@web/static_files/js/libs/jquery.easing.min.js', ['depends' => JqueryAsset::className()]);
}

// If resume later is enabled
if ($formModel->resume) {
    $this->registerJsFile('@web/static_files/js/form.resume.min.js', ['depends' => JqueryAsset::className()]);
}

// If form has rules
if (count($rules) > 0) {
    // Load numeral library
    $this->registerJsFile('@web/static_files/js/libs/numeral.min.js', ['depends' => JqueryAsset::className()]);
    // Load rules engine and run
    $this->registerJsFile('@web/static_files/js/rules.engine.min.js', ['depends' => JqueryAsset::className()]);
    $this->registerJsFile('@web/static_files/js/rules.engine.run.min.js', ['depends' => JqueryAsset::className()]);
}

$this->registerJsFile('@web/static_files/js/libs/jquery.form.js', ['depends' => JqueryAsset::className()]);
// Load embed.js after all
$this->registerJsFile('@web/static_files/js/form.embed.min.js', ['depends' => JqueryAsset::className()]);

// Get form paginated
$formHtml = $pager->getPaginatedData();

// Add honeypot
if ($formModel->honeypot === $formModel::HONEYPOT_ACTIVE) {
    $honeypot = new Honeypot(Html::decode($formHtml));
    $formHtml = $honeypot->getData();
}

// Add theme
if ($showTheme && isset($formModel->theme) && isset($formModel->theme->css) && !empty($formModel->theme->css)) {
    $this->registerCss($formModel->theme->css);
}

// Add custom js file after all
if ($customJS && isset($formModel->ui) && isset($formModel->ui->js_file) && !empty($formModel->ui->js_file)) {
    $this->registerJsFile($formModel->ui->js_file, ['depends' => JqueryAsset::className()]);
}
?>

<div id="form-embed">

    <div id="messages"></div>

    <?= Html::decode($formHtml) ?>

    <div id="progress" class="progress" style="display: none;">
        <div id="bar" class="progress-bar" role="progressbar" style="width: 0;">
            <span id="percent" class="sr-only">0% Complete</span>
        </div>
    </div>
</div>
