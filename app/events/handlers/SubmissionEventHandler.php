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

namespace app\events\handlers;

use Yii;
use yii\base\Component;
use app\helpers\MailHelper;
use app\helpers\SubmissionHelper;

/**
 * Class SubmissionEvent
 * @package app\events
 */
class SubmissionEventHandler extends Component
{

    /**
     * Executed when a submission is received
     *
     * @param $event
     */
    public static function onSubmissionReceived($event)
    {
    }

    /**
     * Executed when a submission is accepted
     *
     * @param $event
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function onSubmissionAccepted($event)
    {

        /** @var \app\models\Form $formModel */
        $formModel = $event->form;
        /** @var \app\models\FormSubmission $formSubmissionModel */
        $formSubmissionModel = $event->submission;
        /** @var \app\models\FormData $formDataModel */
        $formDataModel = $formModel->formData;
        /** @var array $submissionData */
        $submissionData = $formSubmissionModel->getSubmissionData();
        /** @var array $filePaths */
        $filePaths = $event->filePaths;

        /** @var \app\components\queue\MailQueue $mailer */
        $mailer = Yii::$app->mailer;

        // Sender by default: No-Reply Email
        $fromEmail = MailHelper::from(Yii::$app->settings->get("app.noreplyEmail"));

        // Sender verification
        if (empty($fromEmail)) {
            return false;
        }

        /*******************************
        /* Send Notification by e-mail
        /*******************************/

        $formEmailModel = $formModel->formEmail;

        // Form fields
        $fieldsForEmail = $formDataModel->getFieldsForEmail();
        // Submission data in an associative array
        $fieldValues = SubmissionHelper::prepareDataForReplacementToken($submissionData, $fieldsForEmail);
        // Submission data in a multidimensional array: [0 => ['label' => '', 'value' => '']]
        $fieldData = SubmissionHelper::prepareDataForSubmissionTable($submissionData, $fieldsForEmail);

        // Data
        $data = [
            'fieldValues' => $fieldValues, // Submission data for replacement
            'fieldData' => $fieldData, // Submission data for print details
            'formID' => $formModel->id,
            'submissionID' => isset($formSubmissionModel->primaryKey) ? $formSubmissionModel->primaryKey : null,
            'message' => $formEmailModel->message,
        ];

        // Check first: Recipient and Sender are required
        if (isset($formEmailModel->to) && isset($formEmailModel->from) && !empty($formEmailModel->to)) {

            // Views
            $notificationViews = $formEmailModel->getEmailViews();
            // Subject
            $subject = isset($formEmailModel->subject) && !empty($formEmailModel->subject) ?
                $formEmailModel->subject :
                $formModel->name . ' - ' . Yii::t('app', 'New Submission');
            // Token replacement in subject
            $subject = SubmissionHelper::replaceTokens($subject, $fieldValues);
            // ReplyTo (can be an email or an email field)
            $replyTo = $formEmailModel->fromIsEmail() ? $formEmailModel->from :
                Yii::$app->request->post($formEmailModel->from);
            if (empty($replyTo)) {
                // By default, we use the no-reply email address
                $replyTo = Yii::$app->settings->get("app.noreplyEmail");
            }

            // Compose email
            /** @var \app\components\queue\Message $mail */
            $mail = Yii::$app->mailer->compose($notificationViews, $data)
                ->setFrom($fromEmail)
                ->setTo($formEmailModel->to)
                ->setSubject($subject);

            // Add reply to
            if (!empty($replyTo)) {
                $mail->setReplyTo($replyTo);
            }

            // Add cc
            if (!empty($formEmailModel->cc)) {
                $mail->setCc(explode(',', $formEmailModel->cc));
            }

            // Add bcc
            if (!empty($formEmailModel->bcc)) {
                $mail->setBcc(explode(',', $formEmailModel->bcc));
            }

            // Attach files
            if ($formEmailModel->attach && count($filePaths) > 0) {
                foreach ($filePaths as $filePath) {
                    $mail->attach(Yii::getAlias('@app') . DIRECTORY_SEPARATOR . $filePath);
                }
            }

            // Send email to queue
            if (isset(Yii::$app->params['App.Mailer.async']) && Yii::$app->params['App.Mailer.async'] === 1) {
                $mail->queue();
            } else {
                $mail->send();
            }
        }

        /*******************************
        /* Send Confirmation email
        /*******************************/

        $formConfirmationModel = $formModel->formConfirmation;

        // Check first: Send email must be active and Recipient is required
        if ($formConfirmationModel->send_email &&
            isset($formConfirmationModel->mail_to) && !empty($formConfirmationModel->mail_to)) {

            foreach ($formConfirmationModel->mail_to as $mailTo) {
                // To (Get value of email field)
                $to = Yii::$app->request->post($mailTo);
                // Remove all illegal characters from email
                $to = filter_var($to, FILTER_SANITIZE_EMAIL);

                // Validate e-mail
                if (!filter_var($to, FILTER_VALIDATE_EMAIL) === false) {

                    // Views
                    $confirmationViews = $formConfirmationModel->getEmailViews();

                    // Message
                    $data = [
                        'fieldValues' => $fieldValues, // Submission data for replacement
                        'fieldData' => $fieldData, // Submission data for print details
                        'mail_receipt_copy' => (boolean) $formConfirmationModel->mail_receipt_copy, // Add submission copy
                        'message' => isset($formConfirmationModel->mail_message) &&
                        !empty($formConfirmationModel->mail_message) ? $formConfirmationModel->mail_message :
                            Yii::t('app', 'Thanks for your message'),
                    ];

                    // Subject
                    $subject = isset($formConfirmationModel->mail_subject) && !empty($formConfirmationModel->mail_subject) ?
                        $formConfirmationModel->mail_subject : Yii::t('app', 'Thanks for your message');

                    // Token replacement in subject
                    $subject = SubmissionHelper::replaceTokens($subject, $fieldValues);

                    // ReplyTo
                    $replyTo = isset($formConfirmationModel->mail_from) && !empty($formConfirmationModel->mail_from) ?
                        $formConfirmationModel->mail_from : Yii::$app->settings->get("app.noreplyEmail");

                    // Add name to From
                    if (isset($formConfirmationModel->mail_from_name) && !empty($formConfirmationModel->mail_from_name)) {
                        $replyTo = [
                            $replyTo => $formConfirmationModel->mail_from_name,
                        ];
                        $fromEmail = is_array($fromEmail) ? $fromEmail : [
                            $fromEmail => $formConfirmationModel->mail_from_name,
                        ];
                    }

                    // Compose email
                    /** @var \app\components\queue\Message $mail */
                    $mail = Yii::$app->mailer->compose($confirmationViews, $data)
                        ->setFrom($fromEmail)
                        ->setTo($to)
                        ->setSubject($subject);

                    // Add reply to
                    if (!empty($replyTo)) {
                        $mail->setReplyTo($replyTo);
                    }

                    // Attach files
                    // TODO: Don't use formEmailModel here
                    if ($formConfirmationModel->mail_receipt_copy && $formEmailModel->attach && count($filePaths) > 0) {
                        foreach ($filePaths as $filePath) {
                            $mail->attach(Yii::getAlias('@app') . DIRECTORY_SEPARATOR . $filePath);
                        }
                    }

                    // Send email to queue
                    if (isset(Yii::$app->params['App.Mailer.async']) && Yii::$app->params['App.Mailer.async'] === 1) {
                        $mail->queue();
                    } else {
                        $mail->send();
                    }
                }
            }
        }
    }

    /**
     * Executed when a submission is rejected
     *
     * @param $event
     */
    public static function onSubmissionRejected($event)
    {
    }
}
