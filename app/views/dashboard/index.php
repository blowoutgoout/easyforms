<?php

use yii\helpers\Url;
use yii\helpers\Html;
use Carbon\Carbon;

/* @var $this yii\web\View */
/* @var $users int */
/* @var $submissions int */
/* @var $submissionRate int */
/* @var $totalUsers int */
/* @var $totalSubmissions int */
/* @var $totalSubmissionRate int */
/* @var $formsByUsers array */
/* @var $formsBySubmissions array */
/* @var $lastUpdatedForms array */

$this->title = Yii::t("app", "Dashboard");
$this->params['breadcrumbs'][] = Yii::t('app', 'Summary');

$canEditOwnContent = (isset(Yii::$app->user) && Yii::$app->user->can('edit_own_content'));
Carbon::setLocale(substr(Yii::$app->language, 0, 2)); // eg. en-US to en

?>
<div class="dashboard-page">
    <div class="page-header">
        <h1><?= Yii::t('app', 'Dashboard') ?>
            <small><?= Yii::t('app', 'Today Summary') ?></small>
        </h1>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-counter today-views">
                <div class="panel-counter-info">
                    <div class="panel-counter-icon"><i class="glyphicon glyphicon-parents"></i></div>
                    <div class="panel-counter-title">
                        <div class="counter"><?= $users ?></div>
                        <div class="counter-title"><?= Yii::t('app', 'Unique Users') ?></div>
                    </div>
                </div>
                <div class="panel-counter-sub">
                    <h5>
                        <?= Yii::t('app', 'All time Users') ?> <span class="total-counter"><?= $totalUsers ?></span>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-counter today-submissions">
                <div class="panel-counter-info">
                    <div class="panel-counter-icon"><i class="glyphicon glyphicon-send"></i></div>
                    <div class="panel-counter-title">
                        <div class="counter"><?= $submissions ?></div>
                        <div class="counter-title"><?= Yii::t('app', 'Submissions') ?></div>
                    </div>
                </div>
                <div class="panel-counter-sub">
                    <h5><?= Yii::t('app', 'All time Submissions') ?>
                        <span class="total-counter"><?= $totalSubmissions ?></span>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-counter today-submission-rate">
                <div class="panel-counter-info">
                    <div class="panel-counter-icon"><i class="glyphicon glyphicon-charts"></i></div>
                    <div class="panel-counter-title">
                        <div class="counter"><?= $submissionRate ?>%</div>
                        <div class="counter-title"><?= Yii::t('app', 'Submission rate') ?></div>
                    </div>
                </div>
                <div class="panel-counter-sub">
                    <h5><?= Yii::t('app', 'All time Rate') ?>
                        <span class="total-counter"><?= $totalSubmissionRate ?>%</span></h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-counter create-form">
                <?php if ($canEditOwnContent) : ?>
                    <h1>
                        <a href="<?= Url::to(['form/create']) ?>"><span class="glyphicon glyphicon-plus"></span></a>
                    </h1>
                    <h5>
                        <a href="<?= Url::to(['form/create']) ?>"><?= Yii::t('app', 'Create form') ?></a>
                    </h5>
                <?php else : ?>
                    <h1><a href="<?= Url::to(['/form']) ?>"><span class="glyphicon glyphicon-list-alt"></span></a></h1>
                    <h5><a href="<?= Url::to(['/form']) ?>"><?= Yii::t('app', 'View forms') ?></a></h5>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><?= Yii::t('app', 'Most viewed') ?></div>
                <div class="list-group">
                    <?php foreach ($formsByUsers as $form) : ?>
                        <a href="<?= Url::to(['form/analytics', 'id' => $form['id']]) ?>" class="list-group-item">
                            <span class="badge"><?= $form['users'] ?></span> <?= Html::encode($form['name']) ?>
                        </a>
                    <?php endforeach; ?>
                    <?php if (count($formsByUsers) == 0) : ?>
                        <div class="list-group-item"><?= Yii::t('app', 'No views today ') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><?= Yii::t('app', 'Most submitted') ?></div>
                <div class="list-group">
                    <?php foreach ($formsBySubmissions as $form) : ?>
                        <a href="<?= Url::to(['form/submissions', 'id' => $form['id']]) ?>" class="list-group-item">
                            <span class="badge"><?= $form['submissions'] ?></span> <?= Html::encode($form['name']) ?>
                        </a>
                    <?php endforeach; ?>
                    <?php if (count($formsBySubmissions) == 0) : ?>
                        <div class="list-group-item"><?= Yii::t('app', 'No submits today') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><?= Yii::t('app', 'Last updated') ?></div>
                <div class="list-group">
                    <?php foreach ($lastUpdatedForms as $form) : ?>
                        <a href="<?= Url::to(['form/view', 'id' => $form['id']]) ?>"
                           class="list-group-item"><?= Html::encode($form['name']) ?>
                            <span class="label label-info">
                                <?= Carbon::createFromTimestampUTC($form['updated_at'])->diffForHumans() ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                    <?php if (count($lastUpdatedForms) == 0) : ?>
                        <div class="list-group-item"><?= Yii::t('app', 'No data') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
