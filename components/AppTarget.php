<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\components;

use Yii;
use xutl\aliyun\jobs\PushNoticeToMobile;

/**
 * Class AppTarget
 * @package yuncms\notification\components
 */
class AppTarget extends Target
{
    /**
     * Exports notification [[messages]] to a specific destination.
     * Child classes must implement this method.
     */
    public function export()
    {
        if (empty($this->message['subject'])) {
            $this->message['subject'] = Yii::$app->name;
        }

        Yii::$app->queue->push(new PushNoticeToMobile([
            'target' => 'ACCOUNT',
            'targetValue' => $this->message['to_user_id'],
            'title' => $this->message['subject'],
            'body' => wordwrap(implode("\n", $this->message), 70),
        ]));

    }
}