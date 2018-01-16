<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification;

use Yii;
use yuncms\user\models\User;
use yuncms\notification\models\Notification;

/**
 * Class UserTrait
 *
 * @property Module $module
 * @package yuncms\notification
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
}