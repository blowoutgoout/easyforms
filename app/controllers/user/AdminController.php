<?php


namespace app\controllers\user;

use Yii;
use app\modules\user\models\User;
use app\modules\user\models\UserToken;
use app\modules\user\models\UserAuth;
use app\modules\user\models\Role;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Form;
use app\models\FormUser;
use app\helpers\ArrayHelper;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function () {
                            // Check for admin permission
                            // Note: Check for Yii::$app->user first because it doesn't exist in console commands
                            if (!empty(Yii::$app->user) && Yii::$app->user->can("admin")) {
                                return true;
                            }

                            // By Default, Denied Access
                            return false;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * List all User models
     *
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var \app\modules\user\models\search\UserSearch $searchModel */
        $searchModel = Yii::$app->getModule("user")->model("UserSearch");
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Display a single User model
     *
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'user' => $this->findModel($id),
        ]);
    }

    /**
     * Create a new User model. If creation is successful, the browser will
     * be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var \app\modules\user\models\User $user */
        /** @var \app\modules\user\models\Profile $profile */

        $user = Yii::$app->getModule("user")->model("User");
        $user->setScenario("admin");
        $profile = Yii::$app->getModule("user")->model("Profile");

        $post = Yii::$app->request->post();

        if ($user->load($post) && $user->validate() && $profile->load($post) && $profile->validate()) {

            //Save user
            $user->save(false);
            // Save profile
            $profile->setUser($user->id)->save(false);
            // Save forms permissions
            $forms = Yii::$app->request->post('forms');
            if (isset($forms) && is_array($forms)) {
                foreach ($forms as $form_id) {
                    $formUser = new FormUser();
                    $formUser->form_id = $form_id;
                    $formUser->user_id = $user->id;
                    $formUser->save();
                }
            }

            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'The user has been successfully created.'));

            return $this->redirect(['index']);
        }

        // Get all forms
        $forms = Form::find()->select(['id', 'name'])->orderBy('updated_at DESC')->all();
        // Map id => name
        $forms = ArrayHelper::map($forms, 'id', 'name');

        // render
        return $this->render('create', [
            'user' => $user,
            'profile' => $profile,
            'forms' => $forms,
        ]);
    }

    /**
     * Update an existing User model. If update is successful, the browser
     * will be redirected to the 'view' page.
     *
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // set up user and profile
        $user = $this->findModel($id);
        $user->setScenario("admin");
        $profile = $user->profile;

        // load post data and validate
        $post = Yii::$app->request->post();
        if ($user->load($post) && $user->validate() && $profile->load($post) && $profile->validate()) {

            //Save user
            $user->save(false);
            // Save profile
            $profile->setUser($user->id)->save(false);
            // Remove old forms permissions
            FormUser::deleteAll(['user_id' => $user->id]);
            // Save new forms permissions
            $forms = Yii::$app->request->post('forms');
            if (isset($forms) && is_array($forms)) {
                // Set news
                foreach ($forms as $form_id) {
                    $formUser = new FormUser();
                    $formUser->form_id = $form_id;
                    $formUser->user_id = $user->id;
                    $formUser->save();
                }
            }

            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'The user has been successfully updated.'));

            return $this->redirect(['index']);
        }

        // Get all forms
        $forms = Form::find()->select(['id', 'name'])->orderBy('updated_at DESC')->all();
        // Map id => name
        $forms = ArrayHelper::map($forms, 'id', 'name');
        // Get forms of the selected user
        $userForms = FormUser::find()->select(['form_id'])->where(['user_id' => $user->id])->asArray()->all();
        // Get only ids
        $userForms = ArrayHelper::getColumn($userForms, 'form_id');

        // render
        return $this->render('update', [
            'user' => $user,
            'profile' => $profile,
            'forms' => $forms,
            'userForms' => $userForms,
        ]);
    }

    /**
     * Delete an existing User model. If deletion is successful, the browser
     * will be redirected to the 'index' page.
     *
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // delete profile and userkeys first to handle foreign key constraint
        $user = $this->findModel($id);

        // If user has admin role, check if the one
        if ($user->role->id === Role::ROLE_ADMIN &&
            User::find()->where(['role_id' => Role::ROLE_ADMIN])->count() < 2) {

            Yii::$app->session->setFlash(
                'danger',
                Yii::t('app', "You need to create another user with 'Admin' role in order to delete your account.")
            );

        } else {

            $profile = $user->profile;
            UserToken::deleteAll(['user_id' => $user->id]);
            UserAuth::deleteAll(['user_id' => $user->id]);
            FormUser::deleteAll(["user_id" => $user->id]);
            $profile->delete();
            $user->delete();

            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'The user has been successfully deleted.'));

        }

        return $this->redirect(['index']);
    }

    /**
     * Find the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var \app\modules\user\models\User $user */
        $user = Yii::$app->getModule("user")->model("User");
        if (($user = $user::findOne($id)) !== null) {
            return $user;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
