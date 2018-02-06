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
 * @property-read string $relativeTime 时间
 * @package yuncms\notification\frontend\models
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class Notification extends \yuncms\notification\models\Notification
{

    /**
     * @return string 本地化时间
     */
    public function getRelativeTime()
    {
        return Yii::$app->formatter->asRelativeTime($this->created_at);
    }

    /**
     * 获取消息访问Url
     * @return string
     */
    public function getUrl()
    {
        $route = $this->getRoute();
        return !empty($route) ? Url::to($route) : '';
    }

    /**
     * 获取未读通知数量
     * @return int|string
     */
    public static function getCountUnseen()
    {
        return static::find()->andWhere(['read' => false])->andWhere(['in', 'user_id', [0, Yii::$app->user->id]])->count();
    }
}