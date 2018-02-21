<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use app\helpers\Timezone;
use app\helpers\Language;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\Profile $profile
 */

// Lnaguages array
$languages = Language::supportedLanguages();

// Timezone array
$timezones = Timezone::all();
//array_unshift($timezones, "Choose Your Time Zone");

$this->title = Yii::t('app', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-management">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="glyphicon glyphicon-user" style="margin-right: 5px;"></i> <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'id' => 'profile-form',
                'options' => ['enctype' => 'multipart/form-data'],
                'enableAjaxValidation' => true,
            ]); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($profile, 'full_name') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($profile, 'company') ?>
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
                                'browseLabel' =>  Yii::t("app", "Select Photo"),
                                'initialPreview'=>[
                                    Html::img(
                                        $profile->getAvatarUrl(),
                                        [
                                            'class'=>'file-preview-image',
                                            'alt'=> Html::encode($profile->full_name),
                                            'title'=> Html::encode($profile->full_name)
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
                    <?= $form->field($profile, 'timezone')
                        ->dropDownList($timezones)->label(Yii::t("app", "Timezone")) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($profile, 'language')
                        ->dropDownList($languages)->label(Yii::t("app", "Language")) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group" style="text-align: right; margin-top: 10px">
                        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

<?php

$url = Url::to(['/user/avatar-delete', 'id' => $profile->id]);

$js = <<<JS
    jQuery(document).ready(function(){
        // Delete Avatar Event Handler
        $('.fileinput-remove').on('click', function(e) {
            $.ajax({
                method: "POST",
                url: "$url"
            })
            .always(function() {
                // Refresh the page
                location.reload();
            });
        });
    });
JS;
$this->registerJs($js);
