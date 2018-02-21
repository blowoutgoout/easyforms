<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 * @var app\modules\user\models\Profile $profile
 * @var array $forms [id => name] of forms
 * @var array $userForms Form ids of the selected user
 */

// Show username by default
$username = isset($user->username) ? $user->username : $user->id;

$this->title = Yii::t('app', 'Update User') . ': ' . $username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $username, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<div class="user-update box box-big box-light">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Update User') ?>
            <span class="box-subtitle"><?= Html::encode($username) ?></span>
        </h3>
    </div>

    <?= $this->render('_form', [
        'user' => $user,
        'profile' => $profile,
        'forms' => $forms,
        'userForms' => $userForms,
    ]) ?>

</div>

