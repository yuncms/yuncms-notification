<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\components;

use Yii;
use yii\base\Component;

/**
 * 通知的分派器管理
 * You may configure the targets in application configuration, like the following:
 *
 * ```php
 * [
 *     'components' => [
 *         'notification' => [
 *             'targets' => [
 *                 'sms' => [
 *                     'class' => 'yuncms\notification\components\SmsTarget',
 *                     'levels' => ['trace', 'info'],
 *                 ],
 *                 'email' => [
 *                     'class' => 'yuncms\notification\components\EmailTarget',
 *                     'levels' => ['error', 'warning'],
 *                     'message' => [
 *                         'to' => 'admin@example.com',
 *                     ],
 *                 ],
 *                 'app' => [
 *                     'class' => 'yuncms\notification\components\AppTarget',
 *                     'levels' => ['error', 'warning'],
 *                 ],
 *             ],
 *         ],
 *     ],
 * ]
 * ```
 * Each notification target can have a name and can be referenced via the [[targets]] property as follows:
 *
 * ```php
 * Yii::$app->notification->targets['sms']->enabled = false;
 * ```
 * @package yuncms\notification\components
 */
class NotificationManager extends Component
{
    /**
     * @var array|Target[] the notification targets. Each array element represents a single [[Target|notification target]] instance
     * or the configuration for creating the notification target instance.
     */
    public $targets = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        foreach ($this->targets as $name => $target) {
            if (!isset($target['enabled'])) {
                $target['enabled'] = !Yii::$app->user->isGuest;
            }
            if (!$target instanceof Target) {
                $this->targets[$name] = Yii::createObject($target);
            }
        }
    }

    /**
     * Dispatches the notification messages to [[targets]].
     * @param array $messages the notification messages
     */
    public function dispatch($messages)
    {
        foreach ($this->targets as $target) {
            if ($target->enabled) {
                try {
                    $target->collect($messages);
                } catch (\Exception $e) {
                    $target->enabled = false;
                }
            }
        }
    }
}