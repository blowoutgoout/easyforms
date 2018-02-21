<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\forms\LoginForm $model
 */

$this->title = Yii::t('app', 'Sign In');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-login">
    <div class="row">
        <div class="col-xs-12 col-xs-offset-0 col-sm-6 col-md-5 col-md-offset-1 col-lg-4 col-lg-offset-2"
             style="padding-top: 20px">
            <div class="description-wrapper">
                <h3 class="app-slogan">
                    <?= Yii::t(
                        'app',
                        'Welcome to {firstTag} the easiest way {endTag} to build and manage {secondTag} your online forms{endTag}.',
                        [
                            'firstTag' => '<span style="color: #c9d2db">',
                            'secondTag' => '<span style="color: #e8ebef;font-weight: bold;">',
                            'endTag' => '</span>'
                        ]
                    ) ?>
                </h3>
                <div class="hidden-xs">
                    <p><?= Yii::t("app", "Don't have an account?") ?></p>
                    <p><?= Html::a(Yii::t("app", "Sign Up"), ["/user/register"], [
                            'class' => 'btn btn-default',
                        ]) ?></p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4" style="border-left: 1px solid #404b55; padding-top: 20px">
            <div class="form-wrapper">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                ]); ?>

                <?= Html::tag('legend', Html::encode($this->title)) ?>

                <?= $form->field($model, 'username', [
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('username'),
                        'class' => 'form-control',
                    ]])->label(false) ?>

                <?= $form->field($model, 'password', [
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('password'),
                        'class' => 'form-control',
                    ],
                ])->label(false)->passwordInput() ?>

                <div class="checkbox">
                    <?php echo $form->field($model, 'rememberMe')->checkbox(); ?>
                </div>

                <?= Html::submitButton(Yii::t('app', 'Log In'), ['class' => 'btn btn-primary']) ?>

                <div class="links">
                    <?= Yii::t('app', 'Forgot password? Go ahead and') ?>
                    <?= Html::a(Yii::t('app', 'reset it'), ["/user/forgot"]) ?>.
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="sub">
            </div>
        </div>
    </div>
</div>
