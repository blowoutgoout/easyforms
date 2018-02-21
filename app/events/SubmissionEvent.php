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

namespace app\events;

use yii\base\Event;

/**
 * Class SubmissionEvent
 * @package app\events
 */
class SubmissionEvent extends Event
{
    /** @var \app\models\Form */
    public $form;
    /** @var \app\models\FormSubmission */
    public $submission;
    /** @var \yii\web\UploadedFile[] */
    public $files;
    /** @var array File Paths on Disk */
    public $filePaths;
}
