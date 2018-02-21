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

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\Console;

/**
 * Class QueueController
 *
 * @package app\commands
 */
class QueueController extends Controller
{

    /**
     * @var string the default command action.
     */
    public $defaultAction = 'process';

    /**
     * Proccess Mail Queue
     *
     * @return int
     * @throws Exception
     */
    public function actionProcess()
    {

        /** @var \app\components\queue\MailQueue $mailer */
        $mailer = Yii::$app->mailer;
        $success = $mailer->process();

        if ($success) {
            // if all messages are successfully sent out
            $this->stdout(gmdate('Y-m-d H:i:s') . ' : ' .
                Yii::t('app', "All e-mails are successfully sent out.") . "\n", Console::FG_GREEN);
            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout(gmdate('Y-m-d H:i:s') . ' : ' .
            Yii::t('app', "Error sending e-mails.") . "\n", Console::FG_RED);
        return self::EXIT_CODE_ERROR;

    }
}
