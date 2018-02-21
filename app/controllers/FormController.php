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
use yii\helpers\Url;
use SplTempFileObject;
use League\Csv\Writer;
use yii\base\Model;
use app\helpers\Html;
use app\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Role;
use app\models\User;
use app\models\Form;
use app\models\search\FormSearch;
use app\models\FormSubmission;
use app\models\Theme;
use app\models\Template;
use app\models\FormConfirmation;
use app\models\FormData;
use app\models\FormEmail;
use app\models\FormRule;
use app\models\forms\PopupForm;
use app\models\FormUI;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class FormController
 * @package app\controllers
 */
class FormController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'copy' => ['post'],
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // This actions can be performed by any user
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            // Check for user permission
                            if (!empty(Yii::$app->user) && !Yii::$app->user->isGuest) {
                                return true;
                            }
                            return false;
                        }
                    ],
                    [
                        // This actions can be performed by basic users
                        'actions' => ['view', 'share', 'popup-preview', 'popup-code',
                            'analytics', 'stats', 'submissions', 'export-submissions', 'report'],
                        'allow' => true,
                        'matchCallback' => function () {
                            // Check for user permission
                            if (!empty(Yii::$app->user) && !Yii::$app->user->isGuest) {
                                // Form ID
                                $id = Yii::$app->request->getQueryParam('id');
                                return Yii::$app->user->canAccessToForm($id);
                            }
                            return false;
                        }
                    ],
                    [
                        // This actions can be performed by advanced users
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function () {
                            // Check for user permission
                            if (!empty(Yii::$app->user)) {
                                // Check for admin permission
                                if (Yii::$app->user->can('edit_own_content')) {
                                    return true;
                                }
                            }
                            return false;
                        }
                    ],
                    [
                        // This actions can be performed by advanced users with form access
                        'actions' => ['delete-multiple', 'update-status'],
                        'allow' => true,
                        'matchCallback' => function () {
                            // Check for user permission
                            if (!empty(Yii::$app->user)) {
                                if (Yii::$app->user->can('admin')) {
                                    return true;
                                } elseif (Yii::$app->user->can('edit_own_content')) {
                                    $ownIds = Yii::$app->user->getMyFormIds();
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
                        // The rest of actions can be performed by advanced users with form access
                        'allow' => true,
                        'matchCallback' => function () {
                            if (!empty(Yii::$app->user)) {
                                // Check for admin permission
                                if (Yii::$app->user->can('edit_own_content')) {
                                    // Form ID
                                    $id = Yii::$app->request->getQueryParam('id');
                                    return Yii::$app->user->canAccessToForm($id);
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
                'modelClass' => 'app\models\Form',
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
     * Lists all Form models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FormSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Only for admin users
        if (!empty(Yii::$app->user) &&  Yii::$app->user->can("admin") && ($dataProvider->totalCount == 0)) {
            Yii::$app->getSession()->setFlash(
                'warning',
                Html::tag('strong', Yii::t("app", "You don't have any forms!")) . ' ' .
                Yii::t("app", "Click the blue button on the left to start building your first form.")
            );
        }

        // Select slug & name of all promoted templates in the system. Limit to 5 results.
        $templates = Template::find()->select(['slug', 'name'])->where([
            'promoted' => Template::PROMOTED_ON,
        ])->limit(5)->orderBy('updated_at DESC')->asArray()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'templates' => $templates,
        ]);
    }

    /**
     * Show form builder to create a Form model.
     *
     * @param string $template
     * @return string
     */
    public function actionCreate($template = 'default')
    {

        $this->disableAssets();

        return $this->render('create', [
            'template' => $template
        ]);
    }

    /**
     * Show form builder to update Form model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = null)
    {
        $this->disableAssets();

        $model = $this->findFormModel($id);

        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Enable / Disable multiple Forms
     *
     * @param $status
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdateStatus($status)
    {

        $forms = Form::findAll(['id' => Yii::$app->getRequest()->post('ids')]);

        if (empty($forms)) {
            throw new NotFoundHttpException(Yii::t('app', 'Page not found.'));
        } else {
            foreach ($forms as $form) {
                $form->status = $status;
                $form->update();
            }
            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t('app', 'The selected items have been successfully updated.')
            );
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Form model (except id).
     * Updates an existing FormData model (only data field).
     * Updates an existing FormConfirmation model (except id & form_id).
     * Updates an existing FormEmail model (except id & form_id).
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param int|null $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function actionSettings($id = null)
    {
        /** @var \app\models\Form $formModel */
        $formModel = $this->findFormModel($id);
        $formDataModel = $formModel->formData;
        $formConfirmationModel = $formModel->formConfirmation;
        $formEmailModel = $formModel->formEmail;
        $formUIModel = $formModel->ui;

        $postData = Yii::$app->request->post();

        if ($formModel->load($postData) && $formConfirmationModel->load($postData)
            && $formEmailModel->load($postData) && $formUIModel->load($postData)
            && Model::validateMultiple([$formModel, $formConfirmationModel, $formEmailModel, $formUIModel])) {

            // Save data in single transaction
            $transaction = Form::getDb()->beginTransaction();
            try {
                // Save Form Model
                if (!$formModel->save()) {
                    throw new \Exception(Yii::t("app", "Error saving Form Model"));
                }
                // Save data field in FormData model
                if (isset($postData['Form']['name'])) {
                    // Convert JSON Data of Form Data Model to PHP Array
                    /** @var \app\components\JsonToArrayBehavior $builderField */
                    $builderField = $formDataModel->behaviors['builderField'];
                    // Set form name by json key path. If fail, throw \ArrayAccessException
                    $builderField->setSafeValue(
                        'settings.name',
                        $postData['Form']['name']
                    );
                    // Save to DB
                    $builderField->save(); // If fail, throw \Exception
                }

                // Convert data images to stored images in the email messages
                $location = $formModel::FILES_DIRECTORY . '/' . $formModel->id;
                if (!empty($formConfirmationModel->mail_message)) {
                    // Confirmation email message
                    $html = $formConfirmationModel->mail_message;
                    $formConfirmationModel->mail_message = Html::storeBase64ImagesOnLocation($html, $location);
                }
                if (!empty($formEmailModel->message)) {
                    // Notification email message
                    $html = $formEmailModel->message;
                    $formEmailModel->message = Html::storeBase64ImagesOnLocation($html, $location);
                }

                // Save FormConfirmation Model
                if (!$formConfirmationModel->save()) {
                    throw new \Exception(Yii::t("app", "Error saving Form Confirmation Model"));
                }
                // Save FormEmail Model
                if (!$formEmailModel->save()) {
                    throw new \Exception(Yii::t("app", "Error saving Form Email Model"));
                }
                // Save FormUI Model
                if (!$formUIModel->save()) {
                    throw new \Exception(Yii::t("app", "Error saving Form UI Model"));
                }

                $transaction->commit();

                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('app', 'The form settings have been successfully updated')
                );

                return $this->redirect(['index']);
            } catch (\Exception $e) {
                // Rolls back the transaction
                $transaction->rollBack();
                // Rethrow the exception
                throw $e;
            }

        } else {

            if (Yii::$app->user->can('admin')) {
                // Select id & name of all themes in the system
                $themes = Theme::find()->select(['id', 'name'])->asArray()->all();
            } else {
                // Only themes of the current user and administrators
                $userAndAdmins = User::find()->where(['role_id' => Role::ROLE_ADMIN])->asArray()->all();
                $userAndAdmins = ArrayHelper::getColumn($userAndAdmins, 'id');
                $userAndAdmins[] = Yii::$app->user->id;
                $themes = Theme::find()->select(['id', 'name'])
                    ->where(['created_by' => $userAndAdmins])
                    ->asArray()->all();
            }
            $themes = ArrayHelper::map($themes, 'id', 'name');

            return $this->render('settings', [
                'formModel' => $formModel,
                'formDataModel' => $formDataModel,
                'formConfirmationModel' => $formConfirmationModel,
                'formEmailModel' => $formEmailModel,
                'formUIModel' => $formUIModel,
                'themes' => $themes,
            ]);
        }

    }

    /**
     * Displays a single Form Data Model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $formModel = $this->findFormModel($id);

        return $this->render('view', [
            'formModel' => $formModel,
        ]);
    }

    /**
     * Displays a single Form Rule Model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionRules($id)
    {
        $formModel = $this->findFormModel($id);
        $formDataModel = $formModel->formData;

        return $this->render('rule', [
            'formModel' => $formModel,
            'formDataModel' => $formDataModel,
        ]);
    }

    /**
     * Displays share options.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionShare($id)
    {
        $formModel = $this->findFormModel($id);
        $formDataModel = $formModel->formData;
        $popupForm = new PopupForm();

        return $this->render('share', [
            'formModel' => $formModel,
            'formDataModel' => $formDataModel,
            'popupForm' => $popupForm
        ]);
    }

    /**
     * Preview a PopUp Form.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionPopupPreview($id)
    {
        $this->layout = false;

        $popupForm = new PopupForm();

        if ($popupForm->load(Yii::$app->request->post()) && $popupForm->validate()) {

            $formModel = $this->findFormModel($id);
            $formDataModel = $formModel->formData;

            return $this->render('popup-preview', [
                'formModel' => $formModel,
                'formDataModel' => $formDataModel,
                'popupForm' => $popupForm,
            ]);

        }

        return $this->redirect(['form/share', 'id' => $id]);

    }

    /**
     * Displays the PopUp Form Generated Code.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionPopupCode($id)
    {
        $this->layout = false;

        $popupForm = new PopupForm();

        if ($popupForm->load(Yii::$app->request->post()) && $popupForm->validate()) {

            $formModel = $this->findFormModel($id);
            $formDataModel = $formModel->formData;

            return $this->render('popup-code', [
                'formModel' => $formModel,
                'formDataModel' => $formDataModel,
                'popupForm' => $popupForm,
            ]);

        }

        return $this->redirect(['form/share', 'id' => $id]);

    }

    /**
     * Display form performance analytics page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionAnalytics($id)
    {
        $formModel = $this->findFormModel($id);
        $formDataModel = $formModel->formData;

        return $this->render('analytics', [
            'formModel' => $formModel,
            'formDataModel' => $formDataModel,
        ]);
    }

    /**
     * Displays form submissions stats page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionStats($id)
    {
        $formModel = $this->findFormModel($id);
        $formDataModel = $formModel->formData;

        return $this->render('stats', [
            'formModel' => $formModel,
            'formDataModel' => $formDataModel,
        ]);
    }

    /**
     * Reset form submissions stats and performance analytics
     * If the delete is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionResetStats($id)
    {
        // Delete all Stats related to this form
        $rowsDeleted = $this->findFormModel($id)->deleteStats();

        if ($rowsDeleted > 0) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'The stats have been successfully deleted.'));
        } else {
            Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'There are no items to delete.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Copy an existing Form model (and relations).
     * If the copy is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionCopy($id)
    {
        // Source
        $form = $this->findFormModel($id);

        $transaction = Form::getDb()->beginTransaction();

        try {

            // Form
            $formModel = new Form();
            $formModel->attributes = $form->attributes;
            $formModel->id = null;
            $formModel->isNewRecord = true;
            $formModel->save();

            // Form Data
            $formDataModel = new FormData();
            $formDataModel->attributes = $form->formData->attributes;
            $formDataModel->id = null;
            $formDataModel->form_id = $formModel->id;
            $formDataModel->isNewRecord = true;
            $formDataModel->save();

            // Confirmation
            $formConfirmationModel = new FormConfirmation();
            $formConfirmationModel->attributes = $form->formConfirmation->attributes;
            $formConfirmationModel->id = null;
            $formConfirmationModel->form_id = $formModel->id;
            $formConfirmationModel->isNewRecord = true;
            $formConfirmationModel->save();

            // Notification
            $formEmailModel = new FormEmail();
            $formEmailModel->attributes = $form->formEmail->attributes;
            $formEmailModel->id = null;
            $formEmailModel->form_id = $formModel->id;
            $formEmailModel->isNewRecord = true;
            $formEmailModel->save();

            // UI
            $formUIModel = new FormUI();
            $formUIModel->attributes = $form->ui->attributes;
            $formUIModel->id = null;
            $formUIModel->form_id = $formModel->id;
            $formUIModel->isNewRecord = true;
            $formUIModel->save();

            // Conditional Rules
            foreach($form->formRules as $rule){
                $formRuleModel = new FormRule();
                $formRuleModel->attributes = $rule->attributes;
                $formRuleModel->id = null;
                $formRuleModel->form_id = $formModel->id;
                $formRuleModel->isNewRecord = true;
                $formRuleModel->save();
            }

            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'The form has been successfully copied'));

            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'There was an error copying your form.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Form model (and relations).
     * If the delete is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // Delete Form model
        $this->findFormModel($id)->delete();

        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'The form has been successfully deleted'));

        return $this->redirect(['index']);
    }

    /**
     * Show form submissions.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionSubmissions($id = null)
    {
        $formModel = $this->findFormModel($id);
        $formDataModel = $formModel->formData;

        return $this->render('submissions', [
            'formModel' => $formModel,
            'formDataModel' => $formDataModel
        ]);
    }

    /**
     * Export form submissions.
     *
     * @param int $id
     * @param string|null $start
     * @param string|null $end
     */
    public function actionExportSubmissions($id, $start = null, $end = null, $format = 'csv')
    {

        $formModel = $this->findFormModel($id);
        $formDataModel = $formModel->formData;

        $query = FormSubmission::find()
            ->select(['id', 'data', 'created_at'])
            ->where('{{%form_submission}}.form_id=:form_id', [':form_id' => $id])
            ->orderBy('created_at DESC')
            ->with('files');

        if (!is_null($start) && !is_null($end)) {
            $startAt = strtotime(trim($start));
            // Add +1 day to the endAt, if both dates are the same day
            $endAt = $start === $end ? strtotime(trim($end)) + (24 * 60 * 60) : strtotime(trim($end));
            $query->andFilterWhere(['between', '{{%form_submission}}.created_at', $startAt, $endAt]);
        }

        $query->asArray();

        // Insert fields names as the header
        $labels = $formDataModel->getFieldsForEmail();
        $header = array_values($labels);

        // Add File Fields
        $fileFields = $formDataModel->getFileFields();
        $header = array_merge($header, array_values($fileFields)); // Add only labels
        array_unshift($header, '#');
        array_push($header, Yii::t('app', 'Submitted'));
        $keys = array_keys($labels);

        // File Name To Export
        $fileNameToExport = !is_null($start) && !is_null($end) ? $formModel->name . '_' . $start . '_' . $end : $formModel->name;

        if ($format == 'xlsx') {
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('Easy Forms')
                ->setLastModifiedBy('Easy Forms')
                ->setTitle($formModel->name)
                ->setSubject($formModel->name)
                ->setDescription('Spreadsheet document generated by Easy Forms.');
            // Add data
            $arrayData = array(
                $header
            );
            // To iterate the row one by one
            $i = 1;
            foreach ($query->each() as $submission) {
                // $submission represents one row of data from the form_submission table
                $data = json_decode($submission['data'], true);
                // Stringify fields with multiple values
                foreach ($data as $name => &$field) {
                    if (is_array($field)) {
                        $field = implode(', ', $field);
                    }
                }
                // Only take data of current fields
                $fields = [];
                $fields["id"] = $i++;
                foreach ($keys as $key) {
                    $fields[$key] = isset($data[$key]) ? $data[$key] : '';
                }
                // Add files
                $f = 0;
                foreach ($fileFields as $name => $label) {
                    if (isset($submission['files'], $submission['files'][$f])) {
                        $file = $submission['files'][$f];
                        $fileName = $file['name'] .'.'.$file['extension'];
                        $fields[$name] = Url::base(true) . '/' . Form::FILES_DIRECTORY . '/' . $formModel->id . '/' . $fileName;
                    } else {
                        $fields[$name] = '';
                    }
                    $f++;
                }

                $fields["created_at"] = Yii::$app->formatter->asDatetime($submission['created_at']);
                array_push($arrayData, $fields);
            }

            $spreadsheet->getActiveSheet()
                ->fromArray(
                    $arrayData,     // The data to set
                    NULL,   // Array values with this value will not be set
                    'A1'     // Top left coordinate of the worksheet range where
                );

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header(sprintf('Content-Disposition: attachment;filename="%s"', $fileNameToExport . '.xlsx'));
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        } else {
            // Create the CSV into memory
            $csv = Writer::createFromFileObject(new SplTempFileObject());
            $csv->insertOne($header);

            // To iterate the row one by one
            $i = 1;
            foreach ($query->each() as $submission) {
                // $submission represents one row of data from the form_submission table
                $data = json_decode($submission['data'], true);
                // Stringify fields with multiple values
                foreach ($data as $name => &$field) {
                    if (is_array($field)) {
                        $field = implode(', ', $field);
                    }
                }
                // Only take data of current fields
                $fields = [];
                $fields["id"] = $i++;
                foreach ($keys as $key) {
                    $fields[$key] = isset($data[$key]) ? $data[$key] : '';
                }
                // Add files
                $f = 0;
                foreach ($fileFields as $name => $label) {
                    if (isset($submission['files'], $submission['files'][$f])) {
                        $file = $submission['files'][$f];
                        $fileName = $file['name'] .'.'.$file['extension'];
                        $fields[$name] = Url::base(true) . '/' . Form::FILES_DIRECTORY . '/' . $formModel->id . '/' . $fileName;
                    } else {
                        $fields[$name] = '';
                    }
                    $f++;
                }

                $fields["created_at"] = Yii::$app->formatter->asDatetime($submission['created_at']);
                $csv->insertOne($fields);
            }

            $csv->output($fileNameToExport . '.csv');
            exit;
        }
    }

    /**
     * Show form submissions report.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionReport($id = null)
    {
        $formModel = $this->findFormModel($id);
        $formDataModel = $formModel->formData;
        $charts = $formModel->getFormCharts()->asArray()->all();

        return $this->render('report', [
            'formModel' => $formModel,
            'formDataModel' => $formDataModel,
            'charts' => $charts
        ]);
    }

    /**
     * Finds the Form model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * If the user does not have access, a Forbidden Http Exception will be thrown.
     *
     * @param $id
     * @return Form
     * @throws NotFoundHttpException
     */
    protected function findFormModel($id)
    {
        if (($model = Form::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t("app", "The requested page does not exist."));
        }
    }

    /**
     * Disable Assets
     */
    private function disableAssets()
    {
        Yii::$app->assetManager->bundles['app\bundles\AppBundle'] = false;
        Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = false;
        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = false;
        Yii::$app->assetManager->bundles['yii\web\YiiAsset'] = false;
    }
}
