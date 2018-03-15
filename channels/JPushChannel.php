<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\channels;

use yii\base\Component;
use yii\base\InvalidConfigException;
use yuncms\notifications\contracts\ChannelInterface;
use yuncms\notifications\contracts\NotifiableInterface;
use yuncms\notifications\contracts\NotificationInterface;
use JPush\Client as JPush;
use yuncms\notifications\messages\JPushMessage;

/**
 * 极光推送
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class JPushChannel extends Component implements ChannelInterface
{
    public $appKey;
    public $appSecret;

    /**
     * @var JPush
     */
    private $_client;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty ($this->appKey)) {
            throw new InvalidConfigException ('The "appKey" property must be set.');
        }
        if (empty ($this->appSecret)) {
            throw new InvalidConfigException ('The "appSecret" property must be set.');
        }
        $this->_client = new JPush($this->appKey, $this->appSecret);
    }

    /**
     * @param NotifiableInterface $recipient
     * @param NotificationInterface $notification
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /**
         * @var $message JPushMessage
         */
        $message = $notification->exportFor('jpush');
        $appRecipient = $recipient->routeNotificationFor('jpush');

        $this->_client->push()
            ->setPlatform($appRecipient['platform'])
            ->addAlias($appRecipient['id'])
            ->setNotificationAlert($message->body)
            ->send();
    }
}