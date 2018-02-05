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
use yuncms\notification\models\Notification as NotificationModel;

/**
 * Class ScreenChannel
 * @package yuncms\notification\channels
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class ScreenChannel extends Channel
{

    /**
     * send message
     * @param Notification $notification
     * @return mixed|void
     */
    public function send(Notification $notification)
    {
        $className = $notification->className();
        NotificationModel::create([
            'user_id' => $notification->getUserId(),
            'category' => strtolower(substr($className, strrpos($className, '\\') + 1, -12)),
            'action' => $notification->action,
            'message' => (string)$notification->getTitle(),
            'route' => serialize($notification->getRoute()),
            'status' => NotificationModel::STATUS_UNREAD,
        ]);
    }

}
