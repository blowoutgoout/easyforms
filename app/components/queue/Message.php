<?php
/**
 * Message.php
 * @author Saranga Abeykoon http://nterms.com
 */

namespace app\components\queue;

use Yii;
use app\models\Queue;

/**
 * Extends `yii\swiftmailer\Message` to enable queuing.
 *
 * @see http://www.yiiframework.com/doc-2.0/yii-swiftmailer-message.html
 */
class Message extends \yii\swiftmailer\Message
{
    public $attachments = array();

    /**
     * @inheritdoc
     */
    public function attach($fileName, array $options = [])
    {

        array_push($this->attachments, $fileName);

        parent::attach($fileName, $options);

        return $this;
    }


    /**
     * Enqueue the message storing it in database.
     *
     * @return boolean true on success, false otherwise
     */
    public function queue()
    {
        $item = new Queue();
        
        $item->from = serialize($this->getFrom());
        $item->to = serialize($this->getTo());
        $item->cc = serialize($this->getCc());
        $item->bcc = serialize($this->getBcc());
        $item->reply_to = serialize($this->getReplyTo());
        $item->charset = $this->getCharset();
        $item->subject = $this->getSubject();
        $item->attachments = serialize($this->attachments);
        $item->attempts = 0;

        if ($parts = $this->getSwiftMessage()->getChildren()) {
            foreach ($parts as $key => $part) {
                if (!($part instanceof \Swift_Mime_Attachment)) {
                    /* @var $part \Swift_Mime_MimePart */
                    switch ($part->getContentType()) {
                        case 'text/html':
                            $item->html_body = $part->getBody();
                            break;
                        case 'text/plain':
                            $item->text_body = $part->getBody();
                            break;
                    }
                    if (!$item->charset) {
                        $item->charset = $part->getCharset();
                    }
                }
            }
        }

        return $item->save();
    }
}
