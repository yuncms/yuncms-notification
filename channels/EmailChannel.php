<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\channels;

use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yuncms\notification\Channel;
use yuncms\notification\Notification;

/**
 * Class EmailChannel
 * @package yuncms\notification\channels
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class EmailChannel extends Channel
{
    /**
     * @var array the configuration array for creating a [[\yii\mail\MessageInterface|message]] object.
     * Note that the "to" option must be set, which specifies the destination email address(es).
     */
    public $message = [];

    /**
     * @var \yii\mail\MailerInterface|array|string the mailer object or the application component ID of the mailer object.
     * After the EmailChannel object is created, if you want to change this property, you should only assign it
     * with a mailer object.
     */
    public $mailer = 'mailer';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\MailerInterface');
    }

    /**
     * Sends a notification in this channel.
     * @param Notification $notification
     * @throws InvalidConfigException
     */
    public function send(Notification $notification)
    {
        $message = $this->composeMessage($notification);
        $message->send($this->mailer);
    }

    /**
     * Composes a mail message with the given body content.
     * @param Notification $notification the body content
     * @return \yii\mail\MessageInterface $message
     * @throws InvalidConfigException
     */
    protected function composeMessage($notification)
    {
        if(empty($this->message['to'])){
            throw new InvalidConfigException('The "to" option must be set in EmailChannel::message.');
        }
        $message = $this->mailer->compose();
        Yii::configure($message, $this->message);
        $message->setSubject((string)$notification->getTitle());
        $message->setTextBody((string)$notification->getDescription());
        return $message;
    }
}