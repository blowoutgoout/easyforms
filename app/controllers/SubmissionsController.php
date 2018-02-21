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
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\models\FormSubmission;
use app\models\FormSubmissionFile;
use app\models\FormSubmissionComment;
use app\models\FormData;
use app\helpers\SlugHelper;

/**
 * Class SubmissionsController
 * @package app\controllers
 */
class SubmissionsController extends ActiveController
{
    public $modelClass = 'app\models\FormSubmission';
    public $createScenario = 'administration';
    public $updateScenario = 'administration';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Checks the privilege of the current user.
     *
     * @param string $action the ID of the action to be executed
     * @param \yii\base\Model $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // Form ID
        $request = Yii::$app->request;
        $id = $request->post('form_id') ? $request->post('form_id') : $request->getQueryParam('id');
        if ($action == "delete") {
            /** @var FormSubmission $model */
            $id = $model->form_id;
        }
        // If anonymous user or user without access
        if (!empty(Yii::$app->user) && Yii::$app->user->isGuest || !Yii::$app->user->canAccessToForm($id)) {
            // if access should be denied
            throw new ForbiddenHttpException(
                Yii::t("app", "You are not allowed to perform this action.")
            );
        }

    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = function () {
            // Get id param
            $request = Yii::$app->getRequest();
            $id = $request->get('id');
            $q = $request->get('q');
            $start = $request->get('start');
            $end = $request->get('end');

            $query = FormSubmission::find()->where('form_id=:form_id', [':form_id' => $id]);

            if (isset($q)) {
                // if there are non-latin characters
                if (preg_match('/[^\\p{Common}\\p{Latin}]/u', $q)) {
                    $q = substr(json_encode($q), 1, -1);
                }

                $query = FormSubmission::find()
                    ->where('form_id=:form_id', [':form_id' => $id])
                    ->andWhere(['like', 'data', $q, ["\\" => "\\\\\\"]]); // Escape unicode code point
            }

            if (!empty($start) && !empty($end)) {
                $startAt = strtotime(trim($start));
                // Add +1 day to the endAt, if both dates are the same day
                $endAt = $start === $end || date("m/d/Y") === $end ? strtotime(trim($end)) + (24 * 60 * 60) : strtotime(trim($end));
                $query->andFilterWhere(['between', 'created_at', $startAt, $endAt]);
            }

            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->params['GridView.pagination.pageSize'],
                ],
                'sort' => [
                    'defaultOrder' => ['id' => SORT_DESC],
                ]
            ]);
        };

        return $actions;
    }

    public function actionUpdateall()
    {
        // Get ids param
        $request = Yii::$app->getRequest();
        $id = $request->post('id');
        $ids = $request->post('ids');
        $attributes = $request->post('attributes');

        // If anonymous user or user without access
        if (!empty(Yii::$app->user) && Yii::$app->user->isGuest || !Yii::$app->user->canAccessToForm($id)) {
            // if access should be denied
            throw new ForbiddenHttpException(
                Yii::t("app", "You are not allowed to perform this action.")
            );
        }

        // Default
        $success = false;
        $message = Yii::t("app", "No items matched the query");
        $itemsUpdated = 0;

        try {
            // The number of rows updated
            $itemsUpdated = FormSubmission::updateAll($attributes, ['id' => $ids, 'form_id' => $id]);

            if ($itemsUpdated > 0) {
                $success = true;
                $message = Yii::t("app", "Items updated successfully");
            }

        } catch (\Exception $e) {
            // Rethrow the exception
            // throw $e;
            $message = $e->getMessage();
        }

        // Response fornat
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Response to Client
        $res = array(
            'success' => $success,
            'action'  => 'updateall',
            'itemsUpdated' => $itemsUpdated,
            'ids' => $ids,
            'attributes' => $attributes,
            'message' => $message,
        );

        return $res;

    }

    public function actionDeleteall()
    {
        // Get ids param
        $request = Yii::$app->getRequest();
        $id = $request->post('id');
        $ids = $request->post('ids');

        // If anonymous user or user without access
        if (!empty(Yii::$app->user) && Yii::$app->user->isGuest || !Yii::$app->user->canAccessToForm($id)) {
            // if access should be denied
            throw new ForbiddenHttpException(
                Yii::t("app", "You are not allowed to perform this action.")
            );
        }

        // Default
        $success = false;
        $message = "No items matched the query";
        $itemsDeleted = 0;

        try {
            // The number of rows deleted
            $itemsDeleted = 0;
            // Delete one to one for trigger events
            foreach (FormSubmission::find()->where(['id' => $ids, 'form_id' => $id])->all() as $submissionModel) {
                $deleted = $submissionModel->delete();
                if ($deleted) {
                    $itemsDeleted++;
                }
            }
            // Set response
            if ($itemsDeleted > 0) {
                $success = true;
                $message = Yii::t("app", "Items deleted successfully");
            }

        } catch (\Exception $e) {
            // Rethrow the exception
            // throw $e;
            $message = $e->getMessage();
        }

        // Response fornat
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Response to Client
        $res = array(
            'success' => $success,
            'action'  => 'deleteall',
            'itemsDeleted' => $itemsDeleted,
            'ids' => $ids,
            'message' => $message,
        );

        return $res;

    }

    public function verbs()
    {
        $verbs = parent::verbs();
        $verbs[ "upload" ] = ['POST'];
        $verbs[ "delete-file" ] = ['POST'];
        $verbs[ "add-comment" ] = ['POST'];
        $verbs[ "delete-comment" ] = ['POST'];
        return $verbs;
    }

    /**
     * Add Form Submission Comment model
     *
     * @param integer $id Form ID
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionAddComment($id)
    {
        // If anonymous user or user without access
        if (!empty(Yii::$app->user) && Yii::$app->user->isGuest || !Yii::$app->user->canAccessToForm($id)) {
            // if access should be denied
            throw new ForbiddenHttpException(
                Yii::t("app", "You are not allowed to perform this action.")
            );
        }

        // Response fornat
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Get id params
        $request = Yii::$app->getRequest();
        $submissionID = $request->post('submission_id');
        $comment = $request->post('comment');

        try {
            if (!empty($comment)) {
                $commentModel = new FormSubmissionComment();
                $commentModel->form_id = $id;
                $commentModel->submission_id = $submissionID;
                $commentModel->content = $comment;
                if ($commentModel->save()) {
                    return $commentModel->toArray(['id', 'content', 'authorName', 'submitted']);
                } else {
                    die(var_dump($commentModel->getErrors()));
                }
            }
        } catch (\Exception $e) {
            // Rethrow the exception
            // throw $e;
            $message = $e->getMessage();
        }
    }

    /**
     * Delete Form Submission Comment model
     *
     * @param integer $id Form ID
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionDeleteComment($id)
    {
        // If anonymous user or user without access
        if (!empty(Yii::$app->user) && Yii::$app->user->isGuest || !Yii::$app->user->canAccessToForm($id)) {
            // if access should be denied
            throw new ForbiddenHttpException(
                Yii::t("app", "You are not allowed to perform this action.")
            );
        }

        // Get id params
        $request = Yii::$app->getRequest();
        $submissionID = $request->post('submission_id');
        $commentID = $request->post('comment_id');

        // Default
        $success = false;
        $message = "No items matched the query";

        try {
            $commentModel = FormSubmissionComment::findOne($commentID);
            // Check Access to File
            if ($commentModel->form_id == $id) {
                // Delete the model
                $itemDeleted = $commentModel->delete();
                // Set response
                if ($itemDeleted) {
                    $success = true;
                    $message = Yii::t("app", "Items deleted successfully");
                }
            }
        } catch (\Exception $e) {
            // Rethrow the exception
            // throw $e;
            $message = $e->getMessage();
        }

        // Response fornat
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Response to Client
        $res = array(
            'success' => $success,
            'action'  => 'deleteFile',
            'submissionID' => $submissionID,
            'commentID' => $commentID,
            'message' => $message,
        );

        return $res;
    }

    /**
     * Delete Form Submission File model
     *
     * @param integer $id Form ID
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionDeleteFile($id)
    {
        // If anonymous user or user without access
        if (!empty(Yii::$app->user) && Yii::$app->user->isGuest || !Yii::$app->user->canAccessToForm($id)) {
            // if access should be denied
            throw new ForbiddenHttpException(
                Yii::t("app", "You are not allowed to perform this action.")
            );
        }

        // Get id params
        $request = Yii::$app->getRequest();
        $submissionID = $request->post('submission_id');
        $fileID = $request->post('file_id');

        // Default
        $success = false;
        $message = "No items matched the query";

        try {
            $fileModel = FormSubmissionFile::findOne($fileID);
            // Check Access to File
            if ($fileModel->form_id == $id) {
                // Delete the file
                $filePath = $fileModel->getFilePath();
                @unlink($filePath);
                // Delete the model
                $itemDeleted = $fileModel->delete();
                // Set response
                if ($itemDeleted) {
                    $success = true;
                    $message = Yii::t("app", "Items deleted successfully");
                }
            }
        } catch (\Exception $e) {
            // Rethrow the exception
            // throw $e;
            $message = $e->getMessage();
        }

        // Response fornat
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Response to Client
        $res = array(
            'success' => $success,
            'action'  => 'deleteFile',
            'submissionID' => $submissionID,
            'fileID' => $fileID,
            'message' => $message,
        );

        return $res;

    }

    /**
     * Overwrite file
     *
     * @param int $id Form Model id
     * @param int $s_id FormSubmisssion Model id
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionUpload($id, $s_id)
    {
        // If anonymous user or user without access
        if (!empty(Yii::$app->user) && Yii::$app->user->isGuest || !Yii::$app->user->canAccessToForm($id)) {
            // if access should be denied
            throw new ForbiddenHttpException(
                Yii::t("app", "You are not allowed to perform this action.")
            );
        }

        // Response
        $submissionFile = array();

        // Update Files
        foreach ($_FILES as $fieldID => $file) {
            if ($fileModel = FormSubmissionFile::findOne(['submission_id' => $s_id, 'field' => $fieldID])) { // Update File if exists
                // Check Access to File
                if ($fileModel->form_id == $id) {
                    // Delete Old File
                    $filePath = $fileModel->getFilePath();
                    @unlink($filePath);

                    // Update File Model
                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = basename($file['name'], "." . $extension);
                    $fileModel->name = SlugHelper::slug($filename) . "-" . rand(0, 100000) . "-" . $fileModel->submission_id;
                    $fileModel->extension = $extension;
                    $fileModel->size = $file['size'];
                    if (!is_null($fieldID) && !empty($fieldID)) {
                        $fileModel->field = $fieldID;
                    }
                    $fileModel->save();
                    $submissionFile = $fileModel->toArray(['id', 'field', 'label', 'name', 'originalName','extension', 'sizeWithUnit', 'link']);

                    // Save New File
                    $filePath = $fileModel->getFilePath();
                    move_uploaded_file($file['tmp_name'], $filePath);
                }
            } else {
                // Get Form Fields
                $formData = FormData::findOne(['form_id' => $id]);
                $fileFields = $formData->getFileFields();
                // Check if Field exists
                if (array_key_exists($fieldID, $fileFields)) {
                    // Update File Model
                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = basename($file['name'], "." . $extension);
                    $fileModel = new FormSubmissionFile();
                    $fileModel->form_id = $id;
                    $fileModel->submission_id = $s_id;
                    $fileModel->field = $fieldID;
                    $fileModel->label = $fileFields[$fieldID];
                    $fileModel->name = SlugHelper::slug($filename) . "-" . rand(0, 100000) . "-" . $s_id;
                    $fileModel->extension = $extension;
                    $fileModel->size = $file['size'];
                    $fileModel->save();
                    $submissionFile = $fileModel->toArray(['id', 'field', 'label', 'name', 'originalName','extension', 'sizeWithUnit', 'link']);

                    // Save New File
                    $filePath = $fileModel->getFilePath();
                    move_uploaded_file($file['tmp_name'], $filePath);
                }
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $submissionFile;
    }
}
