<?php
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message string Custom Message */
/* @var $fieldValues array Submission data for replacement token */
/* @var $fieldData array Submission data for print details */
/* @var $mail_receipt_copy boolean Includes a Form Submission Copy */
/* @var $formID integer Form ID */
/* @var $submissionID integer Submission ID */

$i = 0; // Used in row background
?>

<style type="text/css">
    table {
        width: 100%;
        border-bottom: 1px solid #eee;
        font-size: 12px;
        line-height: 135%;
        font-family: 'Lucida Grande', 'Lucida Sans Unicode', Tahoma, sans-serif;
    }

    .th-left {
        vertical-align: top;
        color: #222;
        text-align: left;
        padding: 7px 9px 7px 9px;
        border-top: 1px solid #eee;
    }
</style>

<table cellspacing="0" cellpadding="0">
        <tr style="background-color: #6e8292;">
            <th colspan="2" style="color: #ffffff; text-align: left; padding: 10px;">
                <?= Yii::t('app', 'Submission Details') ?>
            </th>
        </tr>
    <?php foreach ($fieldData as $field) : ?>
        <?php if (!empty($field['value']) && (!is_array($field['value']) || !empty($field['value'][0]))): ?>
            <tr style="background-color: <?=($i++%2==1) ? '#f3f5f7' : '#FFFFFF' ?>">
                <th style="text-align: left; padding-left: 10px">
                    <?= $field['label'] ?>
                </th>
                <td style="vertical-align: top; text-align: left; padding: 7px 9px 7px 9px; border-top: 1px solid #eee;">
                    <div style="color: #222;"><?= is_array($field['value']) ? implode(', ', $field['value']) : $field['value'] ?></div>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </table>

    <p><?= Yii::t('app', 'For more details') ?>,
        <a href="<?= Url::to(['form/submissions', 'id' => $formID, '#' => 'view/' . $submissionID ], true) ?>">
            <?= Yii::t('app', 'please click here') ?></a>.</p>
