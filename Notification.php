<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification;

use Yii;
use yii\base\BaseObject;

/**
 * This is the base class for a notification.
 *
 * @property string $category
 * @property integer $userId
 * @property array $data
 */
abstract class Notification extends BaseObject
{
    /**
     * @var string
     */
    public $action;

    /**
     * @var integer
     */
    public $userId = 0;

    /**
     * @var array 通知数据
     */
    public $data = [];

    /**
     * Create an instance
     *
     * @param string $action
     * @param array $params notification properties
     * @return static the newly created Notification
     * @throws \Exception
     */
    public static function create($action, $params = [])
    {
        $params['action'] = $action;
        return new static($params);
    }

    /**
     * Determines if the notification can be sent.
     *
     * @param  Channel $channel
     * @return bool
     */
    public function shouldSend($channel)
    {
        return true;
    }

    /**
     * Gets the notification title
     *
     * @return string
     */
    abstract public function getTitle();

    /**
     * Gets the notification description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return null;
    }

    /**
     * Gets the notification route
     *
     * @return array|null
     */
    public function getRoute()
    {
        return null;
    }

    /**
     * Gets notification data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets notification data
     *
     * @param array $data
     * @return $this
     */
    public function setData($data = [])
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Gets the UserId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the UserId
     *
     * @param integer $id
     * @return $this
     */
    public function setUserId($id)
    {
        $this->userId = $id;
        return $this;
    }

    /**
     * Sends this notification to all channels
     * @throws \yii\base\InvalidConfigException
     */
    public function send()
    {
        Yii::$app->notification->send($this);
    }
}