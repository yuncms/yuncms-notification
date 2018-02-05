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
        NotificationModel::create([
            'to_user_id' => $notification->getUserId(),
            'category' => $notification->category,
            'subject' => (string)$notification->getTitle(),
            'content' => (string)$notification->getDescription(),
            'status' => NotificationModel::STATUS_UNREAD
        ]);

//        $db = Yii::$app->getDb();
//        $className = $notification->className();
//        $currTime = time();
//        $db->createCommand()->insert('notifications', [
//            'class' => strtolower(substr($className, strrpos($className, '\\')+1, -12)),
//            'key' => $notification->key,
//            'message' => (string)$notification->getTitle(),
//            'route' => serialize($notification->getRoute()),
//            'user_id' => $notification->userId,
//            'created_at' => $currTime,
//        ])->execute();
    }

}
