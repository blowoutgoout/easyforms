<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Profile $profile
 * @var array $forms [id => name] of forms
 */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create box box-big box-light">

    <div class="box-header">
        <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
    </div>

    <?= $this->render('_form', [
        'user' => $user,
        'profile' => $profile,
        'forms' => $forms,
    ]) ?>

</div>