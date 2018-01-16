<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\components;

use yii\base\Component;

abstract class Target extends Component
{
    /**
     * @var bool 是否启用此通知目标
     */
    private $_enabled = true;

    /**
     * Exports notification [[messages]] to a specific destination.
     * Child classes must implement this method.
     */
    abstract public function export();

    /**
     * Sets a value indicating whether this notification target is enabled.
     * @param bool|callable $value a boolean value or a callable to obtain the value from.
     *
     * A callable may be used to determine whether the notification target should be enabled in a dynamic way.
     * For example, to only enable a notification if the current user is notification in you can configure the target
     * as follows:
     *
     * ```php
     * 'enabled' => function() {
     *     return !Yii::$app->user->isGuest;
     * }
     * ```
     */
    public function setEnabled($value)
    {
        $this->_enabled = $value;
    }

    /**
     * Check whether the notification target is enabled.
     * @property bool Indicates whether this notification target is enabled. Defaults to true.
     * @return bool A value indicating whether this notification target is enabled.
     */
    public function getEnabled()
    {
        if (is_callable($this->_enabled)) {
            return call_user_func($this->_enabled, $this);
        }
        return $this->_enabled;
    }
}