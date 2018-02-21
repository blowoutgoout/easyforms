<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;

/** @var \app\modules\user\models\Role $role */
$role = Yii::$app->getModule("user")->model("Role");

$this->title = Yii::t('app', 'Site settings');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<div class="account-management">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="glyphicon glyphicon-cogwheels" style="margin-right: 5px;"></i>
                <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class='row'>
                <div class='col-sm-12'>
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "Name"), 'app.name', ['class' => 'control-label']) ?>
                        <?= Html::textInput('app_name', Yii::$app->settings->get('app.name'), ['class' => 'form-control']) ?>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-12'>
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "Description"), 'app_description', ['class' => 'control-label']) ?>
                        <?= Html::textarea('app_description', Yii::$app->settings->get('app.description'), ['class' => 'form-control']) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "Admin e-mail"), 'app_adminEmail', ['class' => 'control-label']) ?>
                        <?= Html::textInput('app_adminEmail', Yii::$app->settings->get('app.adminEmail'), ['class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "Support e-mail"), 'app_supportEmail', ['class' => 'control-label']) ?>
                        <?= Html::textInput('app_supportEmail', Yii::$app->settings->get('app.supportEmail'), ['class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "No-Reply e-mail"), 'app_noreplyEmail', ['class' => 'control-label']) ?>
                        <?= Html::textInput('app_noreplyEmail', Yii::$app->settings->get('app.noreplyEmail'), ['class' => 'form-control']) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "ReCaptcha Site Key"), 'app_reCaptchaSiteKey', ['class' => 'control-label']) ?>
                        <?= Html::textInput('app_reCaptchaSiteKey', Yii::$app->settings->get('app.reCaptchaSiteKey'), ['class' => 'form-control']) ?>
                        <div class="hint-block"><?= Yii::t("app", "Used in the HTML code that displays your forms to your users.") .
                            " <a href='https://www.google.com/recaptcha' target='_blank'>".
                            Yii::t("app", "Get your keys.") ."</a>" ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "ReCaptcha Secret Key"), 'app_reCaptchaSecret', ['class' => 'control-label']) ?>
                        <?= Html::textInput('app_reCaptchaSecret', Yii::$app->settings->get('app.reCaptchaSecret'), ['class' => 'form-control']) ?>
                        <div class="hint-block"><?= Yii::t(
                                "app",
                                "Used for communications between your site and Google. Be careful not to disclose it to anyone."
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?= Html::tag('legend', Yii::t('app', 'Membership'), [
                'class' => 'text-primary',
                'style' => 'font-size: 18px; margin-top: 20px'
            ]); ?>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "Anyone can register"), 'app_anyoneCanRegister', ['class' => 'control-label']) ?>
                        <?= SwitchInput::widget(['name'=>'app_anyoneCanRegister', 'value' => (boolean) Yii::$app->settings->get('app.anyoneCanRegister')]) ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "Use captcha"), 'app_useCaptcha', ['class' => 'control-label']) ?>
                        <?= SwitchInput::widget(['name'=>'app_useCaptcha', 'value' => (boolean) Yii::$app->settings->get('app.useCaptcha')]) ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "Login without password"), 'app_loginWithoutPassword', ['class' => 'control-label']) ?>
                        <?= SwitchInput::widget(['name'=>'app_loginWithoutPassword', 'value' => (boolean) Yii::$app->settings->get('app.loginWithoutPassword')]) ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <?= Html::label(Yii::t("app", "Default user role"), 'app_defaultUserRole', ['class' => 'control-label']) ?>
                        <?= Select2::widget([
                            'name' => 'app_defaultUserRole',
                            'data' => array_reverse($role::dropdown(), true), // Show user role by default,
                            'value' => Yii::$app->settings->get('app.defaultUserRole'),
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group" style="text-align: right; margin-top: 20px">
                        <?= Html::submitButton(Html::tag('i', '', [
                            'class' => 'glyphicon glyphicon-ok',
                        ]) . ' ' . Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
ActiveForm::end();