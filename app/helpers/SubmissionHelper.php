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

namespace app\helpers;

class SubmissionHelper
{
    /**
     * Replace tokens by field values in a text message
     *
     * @param string $text Custom Text Message
     * @param array $data Form Submission Data
     * @return mixed
     */
    public static function replaceTokens($text, array $data)
    {
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $value = is_array($value) ? implode(', ', $value) : $value;
                $text = str_replace("{{".$key."}}", $value, $text);
            } else {
                $text = str_replace("{{".$key."}}", '', $text);
            }
        }

        // Remove unused tokens
        $text = preg_replace("/\{\{(.*)\}\}/", '', $text);

        return $text;
    }

    /**
     * Merge array of Submission Data and Field Values
     *
     * @param array $submissionData [name => value]
     * @param array $fields Fields for email [name => labels]
     * @return array $data ['label' => 'value', 'name' => 'value']
     */
    public static function prepareDataForReplacementToken($submissionData, $fields)
    {
        $data = array();
        foreach ($submissionData as $key => $value) {
            if (isset($fields[$key])) {
                $data[$fields[$key]] = $value;
            }
        }
        return array_merge($submissionData, $data);
    }

    /**
     * Prepare submission data for print the detail table on email messages
     *
     * @param array $submissionData [name => value]
     * @param array $fields Fields for email [name => label]
     * @return array $data [['label' => 'value'], ['label' => 'value']]
     */
    public static function prepareDataForSubmissionTable($submissionData, $fields)
    {
        $data = array();
        foreach ($submissionData as $key => $value) {
            if (isset($fields[$key])) {
                array_push($data, [
                    'label' => $fields[$key],
                    'value' => $value,
                ]);
            }
        }
        return $data;
    }
}