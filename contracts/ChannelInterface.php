<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\contracts;

interface ChannelInterface
{
    public function send(NotifiableInterface $recipient, NotificationInterface $notification);
}