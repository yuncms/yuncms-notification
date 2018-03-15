<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\channels;

use Yii;
use yii\base\Component;
use yii\di\Instance;
use yuncms\helpers\Json;
use yuncms\notifications\contracts\ChannelInterface;
use yuncms\notifications\contracts\NotifiableInterface;
use yuncms\notifications\contracts\NotificationInterface;
use yuncms\notifications\messages\AliyunCloudPushMessage;

/**
 * App 推送渠道
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class AliyunCloudPushChannel extends Component implements ChannelInterface
{
    /**
     * @var string|\xutl\aliyun\Aliyun
     */
    public $aliyun = 'aliyun';

    /**
     * @var string
     */
    public $appKey;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->aliyun = Instance::ensure($this->aliyun, 'xutl\aliyun\Aliyun');
    }

    /**
     * @param NotifiableInterface $recipient
     * @param NotificationInterface $notification
     * @throws \yii\base\InvalidConfigException
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /**
         * @var $message AliyunCloudPushMessage
         */
        $message = $notification->exportFor('aliyunCloudPush');
        $appRecipient = $recipient->routeNotificationFor('aliyunCloudPush');
        if($message->validate()){
            $this->aliyun->getCloudPush()->pushNoticeToAndroid([
                'AppKey' => $this->appKey,
                'Target' => $appRecipient['target'],
                'TargetValue' => $appRecipient['targetValue'],
                'Title' => $message->title,
                'Body' => $message->body,
                'ExtParameters' => Json::encode($message->extParameters),//JSON
            ]);
        } else {
            print_r($message->getErrors());
            exit;
        }
        $this->aliyun->getCloudPush()->pushNoticeToIOS([
            'AppKey' => $this->appKey,
            'Target' => $appRecipient['target'],
            'TargetValue' => $appRecipient['targetValue'],
            'ApnsEnv' => YII_ENV_DEV ? 'DEV' : 'PRODUCT',
            'Title' => $message->title,
            'Body' => $message->body,
            'ExtParameters' => Json::encode($message->extParameters),//JSON
        ]);

        $this->aliyun->getCloudPush()->push([
            'AppKey' => $this->appKey,
            'Target' => $appRecipient['target'],
            'TargetValue' => $appRecipient['targetValue'],
            'DeviceType' => 'ALL',
            'Title' => $message->title,
            'PushType' => 'NOTICE',//表示通知
            'Body' => $message->body,
            'StoreOffline' => 'true',
            //'ExpireTime' => 'YYYY-MM-DDThh:mm:ssZ',
            'ExtParameters' => Json::encode($message->extParameters),//JSON
        ]);
    }
}