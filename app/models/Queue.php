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
use yii\db\Expression;
use app\components\queue\Message;

/**
 * This is the model class for table "{{%mail_queue}}".
 *
 * @property string $from
 * @property string $to
 * @property string $cc
 * @property string $bcc
 * @property string $subject
 * @property string $html_body
 * @property string $text_body
 * @property string $reply_to
 * @property string $charset
 * @property string $attachments
 * @property integer $created_at
 * @property integer $attempts
 * @property integer $last_attempt_time
 * @property integer $sent_time
 */
class Queue extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail_queue}}';
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
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_attempt_time'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'attempts', 'last_attempt_time', 'sent_time'], 'integer'],
            [['to', 'cc', 'bcc', 'subject', 'html_body', 'text_body', 'charset', 'attachments'], 'safe'],
        ];
    }
    
    public function toMessage()
    {
        $from = unserialize($this->from);
        $to = unserialize($this->to);
        
        if (!empty($from) && !empty($to)) {
            $cc = unserialize($this->cc);
            $bcc = unserialize($this->bcc);
            $reply_to = unserialize($this->reply_to);
            $attachments = unserialize($this->attachments);

            $message = new Message();
            $message->setFrom($from)->setTo($to);
            
            if (!empty($cc)) {
                $message->setCc($cc);
            }
            
            if (!empty($bcc)) {
                $message->setBcc($bcc);
            }
            
            if (!empty($reply_to)) {
                $message->setReplyTo($reply_to);
            }

            if (!empty($this->charset)) {
                $message->setCharset($this->charset);
            }
            
            $message->setSubject($this->subject);
            
            if (!empty($this->html_body)) {
                $message->setHtmlBody($this->html_body);
            }
            
            if (!empty($this->text_body)) {
                $message->setTextBody($this->text_body);
            }

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $message->attach($attachment);
                }
            }

            return $message;
        }
        
        return null;
    }
}
