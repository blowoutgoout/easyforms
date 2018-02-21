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

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use app\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use app\components\JsonToArrayBehavior;

/**
 * This is the model class for table "form_data".
 *
 * @property integer $id
 * @property integer $form_id
 * @property string $builder
 * @property string $fields
 * @property string $html
 * @property integer $height
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Form $form
 */
class FormData extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form_data}}';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'builderField' => [
                'class' => JsonToArrayBehavior::className(),
                'jsonFieldName' => 'builder',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id', 'height'], 'required'],
            [['form_id', 'height', 'created_at', 'updated_at'], 'integer'],
            [['builder','fields','html'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'form_id' => Yii::t('app', 'Form ID'),
            'builder' => Yii::t('app', 'Form Builder'),
            'fields' => Yii::t('app', 'Form Fields'),
            'html' => Yii::t('app', 'Form Html'),
            'height' => Yii::t('app', 'Height'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::className(), ['id' => 'form_id']);
    }

    /**
     * Return the label of a field
     *
     * Used only inside this model
     *
     * @param array $field Item saved in fields attribute
     * @param bool|false $isGroup Used by radio and checkbox components
     * @return string
     */
    private function getFieldLabel($field, $isGroup = false)
    {
        if ($isGroup) {
            if (isset($field['groupLabel'])) {
                $label = $field['groupLabel'];
            } else {
                $label = Yii::t('app', 'Group of') . " " . $field['name'];
            }
        } else {
            if (isset($field['label']) && !empty($field['label'])) {
                $label = $field['label'];
            } elseif (isset($field['data-label']) && !empty($field['data-label'])) {
                $label = $field['data-label'];
            } elseif (isset($field['type'], $field['value']) && in_array($field['type'], ['button'])) {
                // For buttons, replace label by value
                $label = $field['value'];
            } else {
                $label = Yii::t('app', 'Field with ID') . ": " . $field['id'];
            }
        }

        return $label;
    }

    /**
     * Return Fields in rule variables format.
     * Required for rule builder
     *
     * Note: Radios are grouped by its name and buttons are excluded
     *
     * @return array
     */
    public function getRuleVariables()
    {
        $allFields = Json::decode($this->fields, true);

        // Exclude submit/image/reset buttons.
        $fields = ArrayHelper::exclude($allFields, ['submit', 'reset', 'image'], 'type');
        // Radios
        $radioFields = ArrayHelper::filter($fields, 'radio', 'type');
        // Group Radios by name
        $radioGroups = ArrayHelper::group($radioFields, 'name');

        $variables = array();

        foreach ($fields as $field) {

            // For Radio Buttons
            // We take the name and label of the group
            // and options item save the value of each radio
            if (isset($field['type']) && $field['type'] === "radio") {

                // Check if the radio group was saved in variables before
                if (isset($field['name']) && isset($variables[$field['name']])) {
                    continue;
                }

                // Get all radios with the same name
                $radios = isset($radioGroups[$field['name']]) ? $radioGroups[$field['name']] : null;

                // Get first radio
                $firstRadio = ArrayHelper::first($radios);

                // Set variable attributes
                $variable = [
                    'name' => $firstRadio['name'],
                    'label' => $this->getFieldLabel($firstRadio, true), // Get groupLabel
                    'fieldType' => 'radio',
                ];

                // Get each radio value, and add to options
                $options = [];
                foreach ($radios as $radio) {
                    $option = [
                        "value" => $radio['value'],
                        "label" => $this->getFieldLabel($radio),
                    ];
                    array_push($options, $option);
                }

                if (count($options) > 0) {
                    $variable['options'] = $options;
                }

                // Save the variable, by the name of the radio group
                $variables[$variable['name']] = $variable;

                continue; // Skip the rest of the current loop iteration
            }

            $variable = [
                'name' => $field['id'], // Because multiple checkbox may have the same name
                'label' => $this->getFieldLabel($field),
                'fieldType' => isset($field['type']) ? $field['type'] : $field['tagName'],
            ];

            $options = [];

            if (isset($field['options'])) { // Select List has options
                foreach ($field['options'] as $option) {
                    $option = [
                        "value" => isset($option['value']) ? $option['value'] : "",
                        "label" => isset($option['label']) ? $option['label'] : "",
                    ];
                    array_push($options, $option);
                }
            }

            if (count($options) > 0) {
                $variable['options'] = $options;
            }

            $variables[$variable['name']] = $variable;
        }

        // Add Form to variables
        $form = [
            'name' => "form",
            'label' => Yii::t('app', "This form"),
            'fieldType' => "form",
        ];

        $variables[$form['name']] = $form;

        return array_values($variables); // Remove keys
    }

    /**
     * Return Fields in rule actions format.
     * Required for rule builder
     *
     * Note: Radios and Checkboxes are grouped by groupLabel and name
     *
     * @return array
     */
    public function getRuleFields()
    {
        $fields = Json::decode($this->fields, true);

        $options = array();

        foreach ($fields as $field) {

            // For Checkboxes and Radio Buttons
            // We take the name and label of the group
            if (isset($field['type']) && ( $field['type'] === "checkbox" || $field['type'] === "radio")) {

                // Check if the field was saved in options before
                if (isset($field['name']) && isset($options[$field['name']])) {
                    continue;
                }

                // Set option attributes
                $option = [
                    'name' => $field['name'],
                    'label' => $this->getFieldLabel($field, true), // Get groupLabel,
                ];

                // Save the option, by the name of the field
                $options[$option['name']] = $option;

                continue; // Skip the rest of the current loop iteration

            }

            $option = [
                'name' => $field['id'],
                'label' => $this->getFieldLabel($field),
            ];

            // For buttons, replace label by value
            if (isset($field['type']) && in_array($field['type'], ['submit', 'reset', 'image', 'button'])) {
                $option['label'] = isset($field['value']) ? $field['value'] : $field['id'];
            }

            $options[$option['name']] = $option;
        }

        return array_values($options); // Remove keys
    }

    /**
     * Return Form Steps in rule actions format.
     * Required for rule builder
     *
     * @return array
     */
    public function getRuleSteps()
    {
        $builder = Json::decode($this->builder, true);
        $steps = $builder['settings']['formSteps']['fields']['steps']['value'];

        $options = array();

        if (count($steps) > 1) {
            foreach ($steps as $index => $title) {
                $option = [
                    'name' => $index,
                    'label' => $title,
                ];
                $options[$option['name']] = $option;
            }
        } else {
            $option = [
                'name' => 0,
                'label' => Yii::t('app', 'Same Step'),
            ];
            $options[$option['name']] = $option;
        }

        return $options;
    }

    /**
     * Return field ids as simple array.
     * Required by rules engine
     *
     * @return array List of all input ids
     */
    public function getFieldIds()
    {
        $fields = Json::decode($this->fields, true);
        $fieldIDs = ArrayHelper::column($fields, 'id', 'id');
        return array_values($fieldIDs); // Only simple array
    }

    /**
     * Return All Fields, except buttons.
     * @return array
     */
    public function getFields()
    {
        $fields = Json::decode($this->fields, true);
        return ArrayHelper::exclude($fields, ['submit', 'reset', 'image'], 'type');
    }

    /**
     * Return All Fields, except buttons.
     * Verifies that each field has a valid label
     *
     * Used by DataValidator
     * @return array
     */
    public function getFieldsForValidation()
    {
        // Required Fields
        $allFields = $this->getFields();
        $fields = [];
        foreach ($allFields as $field) {
            if (!isset($field['label'])) {
                $field['label'] = Yii::t('app', 'the input value');
            }
            if (isset($field['type']) && ($field['type'] == "checkbox" || $field['type'] == "radio") &&
                !isset($field['groupLabel'])) {
                $field['groupLabel'] = Yii::t('app', 'the input value');
            }
            array_push($fields, $field);
        }

        return $fields;
    }

    /**
     * Return required fields.
     *
     * Used by this model
     *
     * @return array
     */
    public function getRequiredFields()
    {
        // Required Fields
        $fields = Json::decode($this->fields, true);
        $requiredFields = ArrayHelper::filter($fields, true, 'required');
        $fields = [];
        foreach ($requiredFields as $field) {
            if (!isset($field['label'])) {
                $field['label'] = Yii::t('app', 'the input value');
            }
            if (isset($field['type']) && ($field['type'] == "checkbox" || $field['type'] == "radio") &&
                !isset($field['groupLabel'])) {
                $field['groupLabel'] = Yii::t('app', 'the input value');
            }
            array_push($fields, $field);
        }

        return $fields;
    }

    /**
     * Return unique fields.
     *
     * Used by DataValidator
     *
     * @return array
     */
    public function getUniqueFields()
    {
        // Unique Fields
        $fields = Json::decode($this->fields, true);
        return ArrayHelper::filter($fields, true, 'data-unique');
    }

    /**
     * Return All Fields, except files and buttons.
     * @return array
     */
    public function getFieldsWithoutFilesAndButtons()
    {
        $fields = Json::decode($this->fields, true);
        return ArrayHelper::exclude($fields, ['submit', 'reset', 'image', 'file'], 'type');
    }

    /**
     * Return Fields without disabled attribute, Exclude buttons.
     * @return array
     */
    public function getEnabledFields()
    {
        $fields = $this->getFields();
        return ArrayHelper::exclude($fields, true, 'disabled');
    }

    /**
     * Return fields for Submissions App
     * Exclude file and button fields
     *
     * @return array
     */
    public function getFieldsForSubmissions()
    {
        // All fields except buttons
        $fields = $this->getFields();
        // Exclude file fields
        $fields = ArrayHelper::exclude($fields, ['file'], 'type');
        // Exclude disabled fields
        $fields = ArrayHelper::exclude($fields, true, 'disabled');

        $options = array();

        foreach ($fields as $field) {

            // For Checkboxes and Radio Buttons
            // We take the name and label of the group
            if (isset($field['type']) && ( $field['type'] === "checkbox" || $field['type'] === "radio")) {

                // Check if the field was saved in options before
                if (isset($field['name']) && isset($options[$field['name']])) {
                    continue;
                }

                // Set option attributes
                $option = [
                    'name' => $field['name'],
                    'label' => $this->getFieldLabel($field, true), // Get groupLabel,
                ];

                // Save the option, by the name of the field
                $options[$option['name']] = $option;

                continue; // Skip the rest of the current loop iteration

            }

            // Default
            $option = [
                'name' => $field['id'],
                'label' => $this->getFieldLabel($field),
            ];
            $options[$option['name']] = $option;
        }

        return array_values($options); // Remove keys
    }

    /**
     * Return an array of field names and labels
     *
     * Used by SubmissionEventHandler and AppController
     * @return array
     */
    public function getFieldsForEmail()
    {
        $submissionFields = $this->getFieldsForSubmissions();
        return ArrayHelper::map($submissionFields, 'name', 'label');
    }

    /**
     * Return All fields labels (name and label as assoc array).
     * Format: [name=>label]
     * In checkbox & radio elements: Replace by [name=>groupLabel]
     *
     * Used by FormController and Form Report view
     * @return array
     */
    public function getLabels()
    {
        // Fields
        $fields = $this->getFields();
        // All Labels
        $allLabels = ArrayHelper::column($fields, 'label', 'name');
        // Checkboxes & Radio Buttons Labels
        $checboxAndRadioLabels = ArrayHelper::column($fields, 'groupLabel', 'name');
        // Replace with Checkboxes & Radio Buttons labels
        $labels = array_merge($allLabels, $checboxAndRadioLabels);
        return $labels;
    }

    /**
     * Return labels of required fields (name and label as assoc array).
     * Format: [name=>label]
     * In checkbox & radio elements: Replace by [name=>groupLabel]
     *
     * Used by DataValidator
     *
     * @return array
     */
    public function getRequiredLabels()
    {
        // Required Fields
        $requiredFields = $this->getRequiredFields();
        // All Labels
        $allRequiredLabels = ArrayHelper::column($requiredFields, 'label', 'name');
        // Checkboxes & Radio Buttons Labels
        $checboxAndRadioLabels = ArrayHelper::column($requiredFields, 'groupLabel', 'name');
        // Replace with Checkboxes & Radio Buttons labels
        $requiredLabels = array_merge($allRequiredLabels, $checboxAndRadioLabels);
        return $requiredLabels;
    }

    /**
     * Return All labels except of files and buttons. (name and label as assoc array).
     * Format: [name=>label]
     * In checkbox & radio elements: Replace by [name=>groupLabel]
     *
     * Used by FormController for export submissions as CSV file
     * @return array
     */
    public function getLabelsWithoutFilesAndButtons()
    {
        // Fields without files and buttons
        $fields = $this->getFieldsWithoutFilesAndButtons();
        // Get its Labels
        $allLabels = ArrayHelper::column($fields, 'label', 'name');
        // Get Checkboxes & Radio Buttons Labels
        $checboxAndRadioLabels = ArrayHelper::column($fields, 'groupLabel', 'name');
        // Replace with Checkboxes & Radio Buttons labels
        $labels = array_merge($allLabels, $checboxAndRadioLabels);
        return $labels;
    }

    /**
     * Return Emails Fields.
     * Format: [name=>label]
     *
     * Used by FormSettings view
     *
     * @return array
     */
    public function getEmailLabels()
    {
        $fields = Json::decode($this->fields, true);
        $emailFields = ArrayHelper::filter($fields, 'email', 'type');
        return ArrayHelper::column($emailFields, 'label', 'name');
    }

    /**
     * Return File Fields.
     * Format: [name=>label]
     *
     * Used by Submissions App and AppController
     * @return array
     */
    public function getFileFields()
    {
        $fields = Json::decode($this->fields, true);
        $fileFields = ArrayHelper::filter($fields, 'file', 'type');
        return ArrayHelper::column($fileFields, 'label', 'name');
    }

    /**
     * Return Required File Labels.
     * Format: [name=>label]
     *
     * Used by DataValidator
     *
     * @return array
     */
    public function getRequiredFileLabels()
    {
        $fields = Json::decode($this->fields, true);
        $fileFields = ArrayHelper::filter($fields, 'file', 'type');
        $requiredFileFields = ArrayHelper::filter($fileFields, true, 'required');
        return ArrayHelper::column($requiredFileFields, 'label', 'name');
    }

    /**
     * Return Values of all options of all Select List of the form.
     * Format: [value1, value2, ...]
     *
     * Used by DataValidator
     *
     * @return array
     */
    public function getOptionValues()
    {
        $fields = Json::decode($this->fields, true);
        $selects = ArrayHelper::filter($fields, 'select', 'tagName');
        $options = [];
        foreach ($selects as $select) {
            if (isset($select['options'])) {
                foreach ($select['options'] as $option) {
                    array_push($options, $option);
                }
            }
        }
        return array_values(ArrayHelper::column($options, 'value', 'value'));
    }

    /**
     * Return Values of all checkboxes of the form.
     * Format: [value1, value2, ...]
     *
     * Used by DataValidator
     *
     * @return array
     */
    public function getCheckboxValues()
    {
        $fields = Json::decode($this->fields, true);
        $radios = ArrayHelper::filter($fields, 'checkbox', 'type');
        return array_values(ArrayHelper::column($radios, 'value', 'id'));
    }

    /**
     * Return Values of all radio buttons of the form.
     * Format: [value1, value2, ...]
     *
     * Used by DataValidator
     *
     * @return array
     */
    public function getRadioValues()
    {
        $fields = Json::decode($this->fields, true);
        $radios = ArrayHelper::filter($fields, 'radio', 'type');
        return array_values(ArrayHelper::column($radios, 'value', 'id'));
    }

    /**
     * Return ID of the first recaptcha component
     *
     * Used by Form model
     *
     * @return null|string
     */
    public function getRecaptchaFieldID()
    {
        // Filter reCaptcha components
        $builder = Json::decode($this->builder, true);
        $recaptchaComponent = ArrayHelper::filter($builder['initForm'], 'recaptcha', 'name');
        // Get the first value of the array
        $component = array_shift($recaptchaComponent);
        return isset($component) && isset($component['fields'])
        && isset($component['fields']['id'])
        && ($component['fields']['id']['value']) ? $component['fields']['id']['value'] : null;
    }

    /**
     * Return Radio Button Fields.
     * Format: [id=>value]
     *
     * Used by FormEventHandler
     * @return array
     */
    public function getRadioFields()
    {
        // Filter radio components
        $fields = Json::decode($this->fields, true);
        $radios = ArrayHelper::filter($fields, 'radio', 'type');

        return ArrayHelper::column($radios, 'value', 'id');
    }

    /**
     * Return Select List Fields.
     * Format: [id=>value]
     *
     * Used by FormEventHandler
     * @return array
     */
    public function getSelectListFields()
    {
        $fields = Json::decode($this->fields, true);
        $selects = ArrayHelper::filter($fields, 'select', 'tagName');
        $options = [];
        foreach ($selects as $select) {
            if (isset($select['options'])) {
                $i = 0;
                foreach ($select['options'] as $option) {
                    // Add field ID to each option
                    $option['id'] = $select['id'] . '_' . $i++;
                    array_push($options, $option);
                }
            }
        }
        return ArrayHelper::column($options, 'value', 'id');
    }

    /**
     * Return Field IDs by container CSS class
     * Format: [id, id]
     *
     * Used by DataValidator
     * @param $cssClass
     * @return array
     */
    public function getFieldsByContainerClass($cssClass = '')
    {
        $fields = [];
        if (is_string($cssClass) && !empty($cssClass)) {
            $builder = Json::decode($this->builder, true);
            foreach ($builder['initForm'] as $field) {
                if (isset($field['fields']['containerClass']['value'])) {
                    $containerClass = $field['fields']['containerClass']['value'];
                    if (count(array_filter(explode('.', $cssClass))) === count(array_intersect(array_filter(explode(' ', $containerClass)), array_filter(explode('.', $cssClass))))) {
                        if (isset($field['fields']['id']['value'])) {
                            array_push($fields, $field['fields']['id']['value']);
                        }
                    }
                }
            }
        }
        return $fields;
    }


}
