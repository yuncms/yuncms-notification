<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\frontend\models;

use Yii;
use yii\helpers\Url;

/**
 * Class Notification
 *
 * @property-read string $url 前台访问Url
 * @package yuncms\notification\frontend\models
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class Notification extends \yuncms\notification\models\Notification
{
    /**
     * 获取消息访问Url
     * @return string
     */
    public function getUrl()
    {
        $route = $this->getRoute();
        return !empty($route) ? Url::to($route) : '';
    }
}