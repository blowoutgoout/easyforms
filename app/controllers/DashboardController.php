<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\db\Query;

/**
 * Class DashboardController
 * @package app\controllers
 */
class DashboardController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {

        if (file_exists(Yii::getAlias('@app/install.php'))) {
            Yii::$app->getSession()->setFlash(
                'danger',
                Yii::t(
                    'app',
                    "For security reasons you must remove the 'install.php' file from your application directory."
                )
            );
        }

        if (!parent::beforeAction($action)) {
            return false;
        }

        return true; // or false to not run the action
    }

    /**
     * Dashboard
     *
     * @return mixed
     */
    public function actionIndex()
    {

        // Filter by user role
        $formIds = [];

        if (!empty(Yii::$app->user)) {
            if (!Yii::$app->user->can("edit_own_content")) {
                // Basic User
                $formIds = Yii::$app->user->getAssignedFormIds();
            } elseif (Yii::$app->user->can("edit_own_content") && !Yii::$app->user->can("admin")) {
                // Advanced User
                $formIds = Yii::$app->user->getMyFormIds();
            }
        }

        // Unique users today
        $usersQuery = new Query;
        $usersQuery->select(['domain_userid'])
            ->from('{{%event}}')
            ->where('DATE(FROM_UNIXTIME(collector_tstamp)) = CURRENT_DATE')
            ->distinct();

        // Unique users total
        $totalUsersQuery = new Query;
        $totalUsersQuery->select(['users'])
            ->from('{{%stats_performance}}')
            ->where('DATE(day) != CURRENT_DATE');

        // Submissions today
        $submissionsQuery = new Query;
        $submissionsQuery->select(['id'])
            ->from('{{%form_submission}}')
            ->andWhere('DATE(FROM_UNIXTIME(created_at)) = CURRENT_DATE');

        // Submissions total
        $totalSubmissionsQuery = new Query;
        $totalSubmissionsQuery->select(['id'])
            ->from('{{%form_submission}}');

        if (!Yii::$app->user->can('admin')) {
            // Add user filter to queries
            $formIds = count($formIds) > 0 ? $formIds : 0; // Important restriction
            $usersQuery->andFilterWhere(['app_id' => $formIds]);
            $totalUsersQuery->andFilterWhere(['app_id' => $formIds]);
            $submissionsQuery->andFilterWhere(['form_id' => $formIds]);
            $totalSubmissionsQuery->andFilterWhere(['form_id' => $formIds]);
        }

        // Execute queries
        $users = $usersQuery->count();
        $totalUsers = $totalUsersQuery->sum('users');
        $submissions = $submissionsQuery->count();
        $totalSubmissions = $totalSubmissionsQuery->count();

        // Add today data to total
        $totalUsers = $totalUsers + $users;

        // Users / submissions = Conversion rate
        $submissionRate = 0;
        if ($users > 0 && $submissions > 0) {
            $submissionRate = round($submissions/$users*100);
        }

        $totalSubmissionRate = 0;
        if ($totalUsers > 0 && $totalSubmissions > 0) {
            $totalSubmissionRate = round($totalSubmissions/$totalUsers*100);
        }

        // Most viewed forms list by unique users
        $formsByUsersQuery = (new Query())
            ->select(['f.id', 'f.name', 'COUNT(DISTINCT(e.domain_userid)) AS users'])
            ->from('{{%event}} AS e')
            ->innerJoin('{{%form}} AS f', 'e.app_id = f.id')
            ->where(['event' => 'pv'])
            ->andWhere('DATE(FROM_UNIXTIME(collector_tstamp)) = CURRENT_DATE')
            ->groupBy(['f.id', 'f.name'])
            ->orderBy('users DESC')
            ->limit(Yii::$app->params['ListGroup.listSize']);

        // Forms list by submissions
        $formsBySubmissionsQuery = (new Query())
            ->select(['f.id', 'f.name', 'COUNT(fs.id) AS submissions'])
            ->from('{{%form_submission}} AS fs')
            ->innerJoin('{{%form}} as f', 'fs.form_id = f.id')
            ->where('DATE(FROM_UNIXTIME(fs.created_at)) = CURRENT_DATE')
            ->groupBy(['f.id', 'f.name'])
            ->orderBy('submissions DESC')
            ->limit(Yii::$app->params['ListGroup.listSize']);

        // Last updated forms list
        $lastUpdatedFormsQuery = (new Query())
            ->select(['id', 'name', 'updated_at'])
            ->from('{{%form}} AS f')
            ->where('DATE(FROM_UNIXTIME(updated_at)) = CURRENT_DATE')
            ->orderBy('updated_at DESC')
            ->limit(Yii::$app->params['ListGroup.listSize']);

        if (!Yii::$app->user->can('admin')) {
            // Add user filter to queries
            $formIds = count($formIds) > 0 ? $formIds : 0; // Important restriction
            $formsByUsersQuery->andFilterWhere(['f.id' => $formIds]);
            $formsBySubmissionsQuery->andFilterWhere(['fs.form_id' => $formIds]);
            $lastUpdatedFormsQuery->andFilterWhere(['f.id' => $formIds]);
        }

        // Execute queries
        $formsByUsers = $formsByUsersQuery->all();
        $formsBySubmissions = $formsBySubmissionsQuery->all();
        $lastUpdatedForms = $lastUpdatedFormsQuery->all();

        return $this->render('index', [
            'users' => $users,
            'submissions' => $submissions,
            'submissionRate' => $submissionRate,
            'totalUsers' => $totalUsers,
            'totalSubmissions' => $totalSubmissions,
            'totalSubmissionRate' => $totalSubmissionRate,
            'formsByUsers' => $formsByUsers,
            'formsBySubmissions' => $formsBySubmissions,
            'lastUpdatedForms' => $lastUpdatedForms,
        ]);
    }
}
