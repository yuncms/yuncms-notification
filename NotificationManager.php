<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification;

use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;

/**
 * 通知的渠道管理
 * You may configure the channels in application configuration, like the following:
 *
 * ```php
 * [
 *     'components' => [
 *         'notification' => [
 *             'channels' => [
 *                 'screen' => [
 *                     'class' => 'yuncms\notification\channels\ScreenChannel',
 *                 ],
 *                 'email' => [
 *                     'class' => 'yuncms\notification\channels\EmailChannel',
 *                     'message' => [
 *                         'from' => 'admin@example.com',
 *                     ],
 *                 ],
 *                 'app' => [
 *                     'class' => 'yuncms\notification\channels\AppChannel',
 *                 ],
 *             ],
 *         ],
 *     ],
 * ]
 * ```
 * @package yuncms\notification
 */
class NotificationManager extends Component
{
    /**
     * @var Channel[] 渠道配置
     */
    public $channels = [];

    /**
     * Send a notification to all channels
     *
     * @param Notification $notification
     * @param array|null $channels
     * @return void return the channel
     * @throws \yii\base\InvalidConfigException
     */
    public function send($notification, array $channels = null)
    {
        if ($channels === null) {
            $channels = array_keys($this->channels);
        }

        foreach ((array)$channels as $id) {
            $channel = $this->getChannel($id);
            if (!$notification->shouldSend($channel)) {
                continue;
            }

            $handle = 'to' . ucfirst($id);
            try {
                if ($notification->hasMethod($handle)) {
                    call_user_func([clone $notification, $handle], $channel);
                } else {
                    $channel->send(clone $notification);
                }
            } catch (\Exception $e) {
                Yii::warning("Notification sending by channel '$id' has failed: " . $e->getMessage(), __METHOD__);
            }
        }
    }

    /**
     * Gets the channel instance
     *
     * @param string $id the id of the channel
     * @return Channel|null return the channel
     * @throws \yii\base\InvalidConfigException
     */
    public function getChannel($id)
    {
        if (!isset($this->channels[$id])) {
            throw new InvalidParamException("Unknown channel '{$id}'.");
        }

        if (!is_object($this->channels[$id])) {
            $this->channels[$id] = $this->createChannel($id, $this->channels[$id]);
        }

        return $this->channels[$id];
    }

    /**
     * Create the channel instance
     *
     * @param string $id
     * @param array $config
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    protected function createChannel($id, $config)
    {
        return Yii::createObject($config, [$id]);
    }
}