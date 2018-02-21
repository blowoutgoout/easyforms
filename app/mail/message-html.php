<?php

use app\helpers\Html;
use app\helpers\SubmissionHelper;

/* @var $this \yii\web\View view component instance */
/* @var $message string Custom Message */
/* @var $fieldValues array Submission data for replacement token */
/* @var $fieldData array Submission data for print details */
/* @var $mail_receipt_copy boolean Includes a Form Submission Copy */

// Token replacement in message
$message = SubmissionHelper::replaceTokens($message, $fieldValues);

echo strip_tags($message, implode('', Html::allowedHtml5Tags()));
