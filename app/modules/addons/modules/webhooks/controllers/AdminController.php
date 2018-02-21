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

namespace app\modules\addons\modules\webhooks\controllers;

use Yii;
use app\modules\addons\modules\webhooks\models\Webhook;
use app\modules\addons\modules\webhooks\models\WebhookSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Form;
use yii\helpers\ArrayHelper;

/**
 * AdminController implements the CRUD actions for Webhook model.
 */
class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'delete-multiple' => [
                'class' => 'app\components\actions\DeleteMultipleAction',
                'modelClass' => 'app\modules\addons\modules\webhooks\models\Webhook',
                'afterDeleteCallback' => function () {
                    Yii::$app->getSession()->setFlash(
                        'success',
                        Yii::t('webhooks', 'The selected items have been successfully deleted.')
                    );
                },
            ],
        ];
    }

    /**
     * Lists all Webhook models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WebhookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Webhook model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Webhook model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Webhook();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t('webhooks', 'The webhook has been successfully created.')
            );
            return $this->redirect(['index']);
        } else {
            // Select id & name of all forms in the system
            $forms = Form::find()->select(['id', 'name'])->orderBy('updated_at DESC')->asArray()->all();
            $forms = ArrayHelper::map($forms, 'id', 'name');
            return $this->render('create', [
                'model' => $model,
                'forms' => $forms,
            ]);
        }
    }

    /**
     * Updates an existing Webhook model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t('webhooks', 'The webhook has been successfully updated.')
            );
            return $this->redirect(['index']);
        } else {
            // Select id & name of all forms in the system
            $forms = Form::find()->select(['id', 'name'])->orderBy('updated_at DESC')->asArray()->all();
            $forms = ArrayHelper::map($forms, 'id', 'name');
            return $this->render('update', [
                'model' => $model,
                'forms' => $forms,
            ]);
        }
    }

    /**
     * Enable / Disable multiple Webhooks
     *
     * @param $status
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdateStatus($status)
    {
        $accounts = Webhook::findAll(['id' => Yii::$app->getRequest()->post('ids')]);

        if (empty($accounts)) {
            throw new NotFoundHttpException(Yii::t('webhooks', 'Page not found.'));
        } else {
            foreach ($accounts as $account) {
                $account->status = $status;
                $account->update();
            }
            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t('webhooks', 'The selected items have been successfully updated.')
            );
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing Webhook model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->getSession()->setFlash('success', Yii::t('webhooks', 'The webhook has been successfully deleted.'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Webhook model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Webhook the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Webhook::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}