<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\contracts;

use yuncms\notifications\messages\BaseMessage;

/**
 * Interface Notification
 * @package yuncms\notifications\contracts
 */
interface NotificationInterface
{
    /**
     * 确定通知将传送到哪个频道
     * @return array
     */
    public function broadcastOn();

    /**
     * 将通知作为给定通道的消息导出。
     * @param string $channel
     * @return BaseMessage
     */
    public function exportFor($channel);
}