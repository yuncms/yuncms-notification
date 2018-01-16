<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification;

use Yii;
use yii\helpers\FileHelper;
use yuncms\user\models\User;

/**
 * Class UserTrait
 *
 * @property Module $module
 * @package yuncms\user
 */
trait NotificationTrait
{
    /**
     * 获取模块配置
     * @param string $key
     * @param null $default
     * @return bool|mixed|string
     */
    public function getSetting($key, $default = null)
    {
        return Yii::$app->settings->get($key, 'notification', $default);
    }

    /**
     * @return null|\yii\base\Module
     */
    public function getModule()
    {
        return Yii::$app->getModule('notification');
    }


    /**
     * 给发送用户通知
     * @param int $fromUserId
     * @param int $toUserId
     * @param string $type
     * @param string $subject
     * @param int $model_id
     * @param string $content
     * @param string $referType
     * @param int $refer_id
     * @return bool
     */
    public static function notify($fromUserId, $toUserId, $type, $subject = '', $model_id = 0, $content = '', $referType = '', $refer_id = 0)
    {
        /*不能自己给自己发通知*/
        if ($fromUserId == $toUserId) {
            return false;
        }
        $toUser = User::findOne($toUserId);
        if (!$toUser) {
            return false;
        }

        try {
            $notify = Notification::create([
                'user_id' => $fromUserId,
                'to_user_id' => $toUserId,
                'type' => $type,
                'subject' => strip_tags($subject),
                'model_id' => $model_id,
                'content' => strip_tags($content),
                'refer_model' => $referType,
                'refer_model_id' => $refer_id,
                'status' => Notification::STATUS_UNREAD
            ]);
            return $notify != false;
        } catch (\Exception $e) {
            return false;
        }
    }

}