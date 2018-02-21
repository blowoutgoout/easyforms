<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\search\UserSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_INLINE,
        'action' => ['index'],
        'method' => 'get',
        'fieldConfig' => ['autoPlaceholder'=>true],
    ]); ?>

    <?php echo $form->field($model, 'email', [
        'addon' => [
            'append' => [
                'content' => Html::button(Yii::t('app', 'Search'), [
                    'type' => 'input',
                    'class'=>'btn btn-primary',
                ]),
                'asButton' => true
            ]
        ]
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>
