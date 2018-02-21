<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 */

// Show username by default
$username = isset($user->username) ? $user->username : $user->id;

// Show inactive status, by default
$userStatus = '<span class="label label-danger"> '.Yii::t('app', 'Inactive').' </span>';
if ($user->status === 1) {
    $userStatus = '<span class="label label-success"> '.Yii::t('app', 'Active').' </span>';
} elseif ($user->status === 2) {
    $userStatus = '<span class="label label-warning"> '.Yii::t('app', 'Unconfirmed email').' </span>';
}

$this->title = $username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view box box-big box-light">
    <div class="pull-right" style="margin-top: -5px">
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ', ['update', 'id' => $user->id], [
            'title' => Yii::t('app', 'Update User'),
            'class' => 'btn btn-sm btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-bin"></span> ', ['delete', 'id' => $user->id], [
            'title' => Yii::t('app', 'Delete User'),
            'class' => 'btn btn-sm btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this user? All data related to this item will be deleted. This action cannot be undone.'),
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'User') ?>
            <span class="box-subtitle"><?= Html::encode($username) ?></span>
        </h3>
    </div>

    <?= DetailView::widget([
        'model' => $user,
        'condensed'=>false,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'hideIfEmpty'=>true,
        'enableEditMode'=> false,
        'options' => [
            'class' => 'kv-view-mode', // Fix hideIfEmpty if enableEditMode is false
        ],
        'attributes' => [
            [
                'group'=>true,
                'label'=>Yii::t('app', 'Account Information'),
                'rowOptions'=>['class'=>'info']
            ],
            'id',
            'email:email',
            [
                'attribute'=>'role',
                'value'=>$user->role->name,
                'label'=>Yii::t('app', 'Role'),
            ],
            [
                'attribute'=>'status',
                'format'=>'raw',
                'value'=>$userStatus,
                'type'=>DetailView::INPUT_SWITCH,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'onText' => Yii::t('app', 'Active'),
                        'offText' => Yii::t('app', 'Inactive'),
                    ]
                ],
            ],
            'banned_reason',
            [
                'group'=>true,
                'label'=>Yii::t('app', 'Profile Information'),
                'rowOptions'=>['class'=>'info']
            ],
            [
                'attribute'=>'profile',
                'value'=>$user->profile->full_name,
                'label'=>Yii::t('app', 'Full Name'),
            ],
            [
                'attribute'=>'profile',
                'value'=>$user->profile->company,
                'label'=>Yii::t('app', 'Company'),
            ],
            [
                'attribute'=>'profile',
                'value'=>$user->profile->timezone,
                'label'=>Yii::t('app', 'Timezone'),
            ],
            [
                'attribute'=>'profile',
                'value'=>$user->profile->language,
                'label'=>Yii::t('app', 'Language'),
            ],
            [
                'group'=>true,
                'label'=>Yii::t('app', 'Additional Information'),
                'rowOptions'=>['class'=>'info']
            ],
            'logged_in_at',
            'created_at',
            'updated_at',
            'logged_in_ip',
            'created_ip',
            'banned_at',
        ],
    ]) ?>

</div>
