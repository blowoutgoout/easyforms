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
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\helpers\ArrayHelper;
use app\models\User;
use app\models\Form;
use app\models\Theme;
use app\models\search\ThemeSearch;

/**
 * Class ThemeController
 * @package app\controllers
 */
class ThemeController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-multiple' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // Actions can be performed by advanced users and admin users
                        'actions' => ['index', 'create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            // Note: Check for Yii::$app->user first because it doesn't exist in console commands
                            if (!empty(Yii::$app->user) && Yii::$app->user->can("edit_own_content")) {
                                return true;
                            }

                            // By Default, Denied Access
                            return false;
                        }
                    ],
                    [
                        'actions' => ['delete-multiple'],
                        'allow' => true,
                        'matchCallback' => function () {
                            if (!empty(Yii::$app->user)) {
                                if (Yii::$app->user->can('admin')) {
                                    return true;
                                } elseif (Yii::$app->user->can('edit_own_content')) {
                                    // If can edit own themes
                                    $ownIds = Yii::$app->user->getMyThemeIds();
                                    $ids = Yii::$app->request->post('ids');
                                    // Only update own forms
                                    foreach ($ids as $id) {
                                        if (!in_array($id, $ownIds)) {
                                            return false;
                                        }
                                    }
                                    return true;
                                }
                            }
                            return false;
                        }
                    ],
                    [
                        'allow' => true,
                        'matchCallback' => function () {
                            if (!empty(Yii::$app->user)) {
                                // Only advanced users with theme access
                                if (Yii::$app->user->can('edit_own_content')) {
                                    // Theme ID
                                    $id = Yii::$app->request->getQueryParam('id');
                                    return Yii::$app->user->canAccessToTheme($id);
                                }
                            }
                            return false;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'delete-multiple' => [
                'class' => '\app\components\actions\DeleteMultipleAction',
                'modelClass' => 'app\models\Theme',
                'afterDeleteCallback' => function () {
                    Yii::$app->getSession()->setFlash(
                        'success',
                        Yii::t('app', 'The selected items have been successfully deleted.')
                    );
                },
            ],
        ];
    }

    /**
     * Lists all Theme models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ThemeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Theme model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'themeModel' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Theme model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $themeModel = new Theme();
        $users = User::find()->select(['id', 'username'])->asArray()->all();
        $users = ArrayHelper::map($users, 'id', 'username');

        if (Yii::$app->user->can('admin')) {
            // Select id & name of all forms in the system
            $forms = Form::find()->select(['id', 'name'])->asArray()->all();
        } else {
            // Only the user forms
            $forms = Form::find()->select(['id', 'name'])->where(['created_by' => Yii::$app->user->id])->asArray()->all();
        }

        if ($themeModel->load(Yii::$app->request->post()) && $themeModel->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'The theme has been successfully created.'));
            return $this->redirect(['view', 'id' => $themeModel->id]);
        } else {
            return $this->render('create', [
                'themeModel' => $themeModel,
                'forms' => $forms,
                'users' => $users,
            ]);
        }
    }

    /**
     * Updates an existing Theme model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $themeModel = $this->findModel($id);
        $users = User::find()->select(['id', 'username'])->asArray()->all();
        $users = ArrayHelper::map($users, 'id', 'username');

        if (Yii::$app->user->can('admin')) {
            // Select id & name of all forms in the system
            $forms = Form::find()->select(['id', 'name'])->asArray()->all();
        } else {
            // Only the user forms
            $forms = Form::find()->select(['id', 'name'])->where(['created_by' => Yii::$app->user->id])->asArray()->all();
        }

        if ($themeModel->load(Yii::$app->request->post()) && $themeModel->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'The theme has been successfully updated.'));
            return $this->redirect(['view', 'id' => $themeModel->id]);
        } else {
            return $this->render('update', [
                'themeModel' => $themeModel,
                'forms' => $forms,
                'users' => $users,
            ]);
        }
    }

    /**
     * Deletes an existing Theme model.
     * If the delete is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'The theme has been successfully deleted.'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Theme model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Theme the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($themeModel = Theme::findOne($id)) !== null) {
            return $themeModel;
        } else {
            throw new NotFoundHttpException(Yii::t("app", "The requested page does not exist."));
        }
    }
}
