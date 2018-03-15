<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\contracts;

/**
 * Interface NotifiableInterface
 * @package yuncms\notifications\contracts
 */
interface NotifiableInterface
{
    /**
     * 确定通知是否可以发送给可通知实体。
     * @param NotificationInterface $notification
     * @return bool
     */
    public function shouldReceiveNotification(NotificationInterface $notification);

    /**
     * 获取可通知实体应该监听的频道。
     * @return array
     */
    public function viaChannels();

    /**
     * 获取给定通道的通知路由信息。
     * @param string $channel
     * @return mixed
     */
    public function routeNotificationFor($channel);
}