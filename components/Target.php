<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\components;

use yii\base\Component;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

abstract class Target extends Component
{
    /**
     * @var string target id.
     */
    private $_id;

    /**
     * @var string target name.
     */
    private $_name;

    /**
     * @var string target title to display in views.
     */
    private $_title;

    /**
     * @var bool 是否启用此通知目标
     */
    private $_enabled = true;

    /**
     * @param string $id target id.
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string target id
     */
    public function getId()
    {
        if (empty($this->_id)) {
            $this->_id = $this->getName();
        }
        return $this->_id;
    }

    /**
     * @param string $name target name.
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string target name.
     */
    public function getName()
    {
        if ($this->_name === null) {
            $this->_name = $this->defaultName();
        }
        return $this->_name;
    }

    /**
     * @param string $title target title.
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * @return string target title.
     */
    public function getTitle()
    {
        if ($this->_title === null) {
            $this->_title = $this->defaultTitle();
        }
        return $this->_title;
    }

    /**
     * Generates target name.
     * @return string target name.
     */
    protected function defaultName()
    {
        return Inflector::camel2id(StringHelper::basename(get_class($this)), '');
    }

    /**
     * Generates target title.
     * @return string target title.
     */
    protected function defaultTitle()
    {
        return StringHelper::basename(get_class($this));
    }

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