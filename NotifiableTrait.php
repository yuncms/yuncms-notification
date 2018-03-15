<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;

use yii\helpers\Inflector;
use yuncms\notifications\contracts\NotificationInterface;

/**
 * Class NotifiableTrait
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
trait NotifiableTrait
{
    /**
     * 确定通知实体是否应通过签入通知设置来接收通知。
     * @param NotificationInterface $notification
     * @return bool
     */
    public function shouldReceiveNotification(NotificationInterface $notification)
    {
        $alias = get_class($notification);
        if (isset($this->notificationSettings)) {
            $settings = $this->notificationSettings;
            if (array_key_exists($alias, $settings)) {
                if ($settings[$alias] instanceof \Closure) {
                    return call_user_func($settings[$alias], $notification);
                }
                return (bool)$settings[$alias];
            }
        }
        return true;
    }

    /**
     * 默认通过电子邮件发送通知
     * @return array
     */
    public function viaChannels()
    {
        return ['mail'];
    }

    /**
     * 返回给定通道的通知路由信息。
     * ```php
     * public function routeNotificationForMail() {
     *      return $this->email;
     * }
     * ```
     * @param $channel string
     * @return mixed
     */
    public function routeNotificationFor($channel)
    {
        if (method_exists($this, $method = 'routeNotificationFor' . Inflector::camelize($channel))) {
            return $this->{$method}();
        }
        switch ($channel) {
            case 'aliyunCloudPush':
                return $this->id;
            case 'mail':
                return $this->email;
            case 'jpush':
                return $this->id;
            case 'sms':
                return $this->mobile;
        }
    }
}