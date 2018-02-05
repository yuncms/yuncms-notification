<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\channels;

use Yii;
use yuncms\notification\Channel;
use yuncms\notification\Notification;
use xutl\aliyun\jobs\PushNoticeToMobile;

/**
 * Class EmailChannel
 * @package yuncms\notification\channels
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class AppChannel extends Channel
{
    /**
     * Sends a notification in this channel.
     * @param Notification $notification
     */
    public function send(Notification $notification)
    {
        $message = $this->composeMessage($notification);
        Yii::$app->queue->push($message);
    }

    /**
     * Composes a app message with the given body content.
     * @param Notification $notification the body content
     * @return PushNoticeToMobile $message
     */
    protected function composeMessage($notification)
    {
        return new PushNoticeToMobile([
            'target' => 'ACCOUNT',
            'targetValue' => $notification->userId,
            'title' => (string)$notification->getTitle(),
            'body' => (string)$notification->getDescription(),
            'extParameters' => $notification->getData()
        ]);
    }
}