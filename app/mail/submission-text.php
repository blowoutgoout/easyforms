<?php

use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message string Custom Message */
/* @var $fieldValues array Submission data for replacement token */
/* @var $fieldData array Submission data for print details */
/* @var $mail_receipt_copy boolean Includes a Form Submission Copy */
/* @var $formID integer Form ID */
/* @var $submissionID integer Submission ID */

?>
<?= Yii::t('app', 'Submission Details') ?>:

<?php foreach ($fieldData as $field) : ?>
    <?php if (!empty($field['value']) && (!is_array($field['value']) || !empty($field['value'][0]))): ?>
        - <?= $field['label'] ?>: <?= is_array($field['value']) ? implode(', ', $field['value']) : $field['value'] ?>
    <?php endif; ?>
<?php endforeach; ?>

<?= Yii::t('app', 'For more details') ?>,
<?= Yii::t('app', 'please go here') ?>:
<?= Url::to(['form/submissions', 'id' => $formID, '#' => 'view/' . $submissionID ], true) ?>
