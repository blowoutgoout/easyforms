<?php

use app\helpers\SubmissionHelper;

/* @var $this \yii\web\View view component instance */
/* @var $message string Custom Message */
/* @var $fieldValues array Submission data for replacement token */
/* @var $fieldData array Submission data for print details */
/* @var $mail_receipt_copy boolean Includes a Form Submission Copy */

// Token replacement in message
$message = SubmissionHelper::replaceTokens($message, $fieldValues);
?>
<?= strip_tags($message); ?>

<?php if ($mail_receipt_copy && count($fieldData) > 0) : ?>

    <?= Yii::t('app', 'Submission Details') ?>:

    <?php foreach ($fieldData as $field) : ?>
        <?php if (!empty($field['value']) && (!is_array($field['value']) || !empty($field['value'][0]))): ?>
            - <?= $field['label'] ?>: <?= is_array($field['value']) ? implode(', ', $field['value']) : $field['value'] ?>
        <?php endif; ?>
    <?php endforeach; ?>

<?php endif; ?>
