<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use app\helpers\Timezone;
use app\helpers\Language;

// Lnaguages array
$languages = Language::supportedLanguages();

// Timezone array
$timezones = Timezone::all();
//array_unshift($timezones, "Choose Your Time Zone");

/** @var \app\modules\user\models\Role $role */
$role = Yii::$app->getModule("user")->model("Role");

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 * @var app\models\Profile $profile
 * @var yii\widgets\ActiveForm $form
 * @var array $forms [id => name] of forms
 * @var array $userForms Form ids of the selected user
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <fieldset>
        <legend class="text-primary"><small><?= Yii::t('app', 'Account Info') ?></small></legend>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($user, 'username')
                    ->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter a unique username...')]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($user, 'email')
                    ->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter a unique e-mail...')]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($user, 'newPassword')
                    ->passwordInput(['placeholder' => Yii::t('app', 'Enter password...')])
                    ->label($user->isNewRecord ? Yii::t('app', 'Password') : Yii::t('app', 'Change Password')) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($user, 'status')->dropDownList($user::statusDropdown()); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($user, 'role_id')->widget(Select2::classname(), [
                    'data' => array_reverse($role::dropdown(), true), // Show user role by default
                    'hideSearch' => true,
                    'pluginEvents' => [
                        "select2:select" => "function(e) {
                            if( e.params.data.id == '2' ) {
                                $('#formList').show();
                            } else {
                                $('#formList').hide();
                            }
                        }",
                    ],
                ])->label(Yii::t('app', 'Role')); ?>
            </div>
            <div class="col-sm-6" id="formList">
                <?= Html::label(Yii::t('app', 'Grant Access')); ?>
                <?= Select2::widget([
                    'name' => 'forms',
                    'data' => $forms,
                    'value' => isset($userForms) ? $userForms : null,
                    'options' => ['placeholder' => Yii::t('app', 'Select forms...'), 'multiple' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
                <p class="help-block"><?= Yii::t('app', 'By default, users have no access.') ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <?php // use checkbox for banned_at ?>
                <?php // convert `banned_at` to int so that the checkbox gets set properly ?>
                <div class="form-group">
                    <?php $user->banned_at = $user->banned_at ? 1 : 0 ?>
                    <?php // Html::activeCheckbox($user, 'banned_at', ['label' => Yii::t('app', 'Banned') ]); ?>
                    <?= $form->field($user, 'banned_at')->widget(SwitchInput::classname(), [
                        'pluginEvents' => [
                            "switchChange.bootstrapSwitch" => "function(event, state) {
                                console.log(event, state);
                                if( state == true ) {
                                    $('#banReason').show();
                                } else {
                                    $('#banReason').hide();
                                }
                            }",
                        ],
                    ])->label(Yii::t('app', 'Banned')); ?>
                    <?= Html::error($user, 'banned_at'); ?>
                </div>
            </div>
            <div class="col-sm-10" id="banReason" style="display: none">
                <?= $form->field($user, 'banned_reason')
                    ->textInput(['placeholder' => Yii::t('app', 'When the user tries to log in, it shows this message.')]); ?>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend class="text-primary"><small><?= Yii::t('app', 'Profile Info') ?></small></legend>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($profile, 'full_name')
                    ->textInput(['placeholder' => Yii::t('app', 'Enter user full name...')]); ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($profile, 'company')
                    ->textInput(['placeholder' => Yii::t('app', 'Enter user company...')]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?php
                if ($profile->avatar) {
                    echo $form->field($profile, 'image')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'pluginOptions' => [
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                            'browseLabel' =>  Yii::t("app", "Select Picture"),
                            'initialPreview'=>[
                                Html::img(
                                    $profile->getAvatarUrl(),
                                    [
                                        'class'=>'file-preview-image',
                                        'alt'=> $profile->full_name,
                                        'title'=> $profile->full_name
                                    ]
                                )
                            ],
                            'overwriteInitial'=> true,
                            'showRemove' => false,
                            'showUpload' => false
                        ]
                    ]);
                } else {
                    echo $form->field($profile, 'image')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'pluginOptions' => [
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                            'browseLabel' =>  Yii::t("app", "Select Photo"),
                            'showRemove' => false,
                            'showUpload' => false
                        ]
                    ]);
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($profile, 'timezone')->dropDownList($timezones)->label(Yii::t("app", "Timezone")) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($profile, 'language')->dropDownList($languages)->label(Yii::t("app", "Language")) ?>
            </div>
        </div>
    </fieldset>

    <div class="form-group">
        <?= Html::submitButton($user->isNewRecord ? Yii::t('app', 'Create') :
            Yii::t('app', 'Update'), ['class' => $user->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<< SCRIPT
$( document ).ready(function() {

    // User Role SelectList
    if( $('#user-role_id').val() == '2' ){
        $('#formList').show();
    } else {
        $('#formList').hide();
    }

    // User Banned Checkbox
    $('#user-banned_at').on('init.bootstrapSwitch', function(event, state) {
        if( this.checked ) {
            $('#banReason').show();
        } else {
            $('#banReason').hide();
        }
    });

});
SCRIPT;

$this->registerJs($js, $this::POS_END, 'admin-user-form');

?>