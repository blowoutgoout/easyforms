<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.3.5
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use SplTempFileObject;
use League\Csv\Writer;
use app\models\Form;
use app\models\FormSubmission;

class CsvController extends Controller
{

    /**
     * @var string the default command action.
     */
    public $defaultAction = 'export-submissions';

    /**
     * Export Form Submissions as CSV
     *
     * Eg. php yii csv/export-submissions 1
     */
    public function actionExportSubmissions($id)
    {

        try {


            $formModel = $this->findFormModel($id);
            $formDataModel = $formModel->formData;

            $query = FormSubmission::find()
                ->select(['id', 'data', 'created_at'])
                ->where('{{%form_submission}}.form_id=:form_id', [':form_id' => $id])
                ->orderBy('created_at DESC')
                ->with('files')
                ->asArray();

            // Create the CSV into memory
            $csv = Writer::createFromFileObject(new SplTempFileObject());

            // Insert fields names as the CSV header
            $labels = $formDataModel->getFieldsForEmail();
            $header = array_values($labels);
            // Add File Fields
            $fileFields = $formDataModel->getFileFields();
            $header = array_merge($header, array_values($fileFields)); // Add only labels
            array_unshift($header, '#');
            array_push($header, Yii::t('app', 'Submitted'));
            $keys = array_keys($labels);
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
                        $fields[$name] = Form::FILES_DIRECTORY . '/' . $formModel->id . '/' . $fileName;
                    } else {
                        $fields[$name] = '';
                    }
                    $f++;
                }

                $fields["created_at"] = Yii::$app->formatter->asDatetime($submission['created_at']);
                $csv->insertOne($fields);
            }

            // Print to the output stream
            $csv->output($formModel->name . '.csv');

        } catch (\Exception $e) {

            throw new Exception($e->getMessage());

        }

    }

    /**
     * Finds the Form model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * If the user does not have access, a Forbidden Http Exception will be thrown.
     *
     * @param $id
     * @return Form
     * @throws Exception
     */
    protected function findFormModel($id)
    {
        if (($model = Form::findOne($id)) !== null) {
            return $model;
        } else {
            throw new Exception("The requested Form ID does not exist.");
        }
    }
}