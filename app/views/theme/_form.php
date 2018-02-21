<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\color\ColorInput;
use kartik\select2\Select2;
use app\bundles\ThemeEditorBundle;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $themeModel app\models\Theme */
/* @var $forms array [id => name] of form models */
/* @var $users array [id => username] of user models */

ThemeEditorBundle::register($this);

$data = array();

// Set data for select2 widget
foreach ($forms as $option) {
    $key = Url::to(['app/preview', 'id' => $option['id']], true);
    $data[$key] = $option['name'];
}

// PHP options required by editor.js
$options = array(
    "css" => "#theme-css",
    "iframe" => "formI"
);

// Pass php options to javascript, and load beofre ThemeEditorBundle
$this->registerJs("var options = ".json_encode($options).";", $this::POS_BEGIN, 'editor-options');

?>

<div class="theme-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($themeModel, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($themeModel, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($themeModel, 'color')->widget(ColorInput::classname(), [
        'options' => ['placeholder' => Yii::t("app", "Select color ...")],
        'noSupport' => Yii::t('app', 'It is recommended you use an upgraded browser to display the {type} control properly.'),
        'pluginOptions'=> ['preferredFormat' => 'hex']
    ])->hint(Yii::t("app", "Your theme main color. Value must be a 6 character hex value starting with a '#'.")); ?>

    <div class="form-group">
        <label class="control-label"><?= Yii::t("app", "Live Preview") ?></label>
        <?php echo Select2::widget([
                'name' => 'preview',
                'data' => $data,
                'options' => [
                    'placeholder' => Yii::t("app", "Choose a form"),
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "select2:select" => "previewSelected",
                    "select2:unselect" => "previewUnselected"
                ]
            ]);
        ?>
    </div>

    <!-- Preview panel -->
    <div class="panel panel-default" id="preview-container" style="display:none;">
        <div class="panel-heading clearfix">
            <div class="summary pull-left"><strong><?= Yii::t("app", "Preview") ?></strong></div>
            <div class="pull-right">
                <a id="resizeFull" class="toogleButton" href="javascript:void(0)">
                    <i class="glyphicon glyphicon-resize-full"></i>
                </a>
                <a id="resizeSmall" class="toogleButton" style="display: none" href="javascript:void(0)">
                    <i class="glyphicon glyphicon-resize-small"></i>
                </a>
            </div>
        </div>
        <div class="panel-body" id="preview">
        </div>
    </div>

    <?= $form->field($themeModel, 'css')->hiddenInput() ?>

    <div class="form-group">
        <div id="editor" class="form-control"></div>
    </div>

    <?php if (Yii::$app->user->can('admin')): ?>
        <?= $form->field($themeModel, 'created_by')->widget(Select2::classname(), [
            'data' => $users,
            'options' => ['placeholder' => 'Select a username ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($themeModel->isNewRecord ?
            Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $themeModel->isNewRecord ?
            'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>