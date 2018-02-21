<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = Yii::t('app', 'Mail Server settings');

$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<div class="account-management">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="glyphicon glyphicon-inbox-out" style="margin-right: 5px;"></i>
                <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>
            <?php /* @var $settings */
            foreach ($settings as $index => $setting) {
                if ($setting->key == "host") {
                    echo "<div class='row'><div class='col-sm-8'>";
                    echo $form->field($setting, "[$index]value")->label(Yii::t("app", "Host"));
                    echo "</div>";
                } elseif ($setting->key == "port") {
                    echo "<div class='col-sm-2'>";
                    echo $form->field($setting, "[$index]value")->label(Yii::t("app", "Port"));
                    echo "</div>";
                } elseif ($setting->key == "encryption") {
                    echo "<div class='col-sm-2'>";
                    echo $form->field($setting, "[$index]value")->dropDownList(
                        ['tls'=>'tls', 'ssl'=>'ssl', 'none'=>'none']
                    )->label(Yii::t("app", "Encryption"));
                    echo "</div></div>";
                } elseif ($setting->key == "username") {
                    echo "<div class='row'><div class='col-sm-12'>";
                    echo $form->field($setting, "[$index]value")->label(Yii::t("app", "Username"));
                    echo "</div></div>";
                } elseif ($setting->key == "password") {
                    echo "<div class='row'><div class='col-sm-12'>";
                    echo $form->field($setting, "[$index]value")->passwordInput()
                        ->label(Yii::t("app", "Password"))
                        ->hint(Yii::t('app', 'Please re-enter your password before submit this form.'));
                    echo "</div></div>";
                } else {
                    echo "<div class='row'><div class='col-sm-12'>";
                    echo $form->field($setting, "[$index]value")->label($setting->key);
                    echo "</div></div>";
                }
            }
            ?>
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