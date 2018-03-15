<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;

use Closure;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yuncms\notifications\contracts\NotifiableInterface;
use yuncms\notifications\contracts\NotificationInterface;

/**
 * 通知管理
 *
 * ```php
 * [
 *     'components' => [
 *         'notification' => [
 *             'channels' => [
 *                 'mail' => [
 *                     'class' => 'yuncms\notifications\channels\EmailChannel',
 *                     'from' => 'admin@example.com',
 *                 ],
 *                 'aliyunCloudPushChannel' => [
 *                     'class' => 'yuncms\notifications\channels\AliyunCloudPushChannel',
 *                     'appKey' => 'abcd'
 *                 ],
 *                 'jPush' => [
 *                     'class' => 'yuncms\notifications\channels\JPushChannel',
 *                 ],
 * '               sms' => [
 *                     'class' => 'yuncms\notifications\channels\SmsChannel',
 *                 ],
 *             ],
 *         ],
 *     ],
 * ]
 * ```
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class NotificationManager extends Component
{
    /**
     * @var array shared channel instances indexed by their IDs
     */
    private $_channels = [];

    /**
     * @var array channel definitions indexed by their IDs
     */
    private $_definitions = [];

    /**
     * Getter magic method.
     * This method is overridden to support accessing channels like reading properties.
     * @param string $name channel or property name
     * @return mixed the named property value
     * @throws InvalidConfigException
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        if ($this->has($name)) {
            return $this->get($name);
        }

        return parent::__get($name);
    }

    /**
     * Checks if a property value is null.
     * This method overrides the parent implementation by checking if the named channel is loaded.
     * @param string $name the property name or the event name
     * @return bool whether the property value is null
     */
    public function __isset($name)
    {
        if ($this->has($name)) {
            return true;
        }

        return parent::__isset($name);
    }

    /**
     * Returns a value indicating whether the locator has the specified channel definition or has instantiated the channel.
     * This method may return different results depending on the value of `$checkInstance`.
     *
     * - If `$checkInstance` is false (default), the method will return a value indicating whether the locator has the specified
     *   channel definition.
     * - If `$checkInstance` is true, the method will return a value indicating whether the locator has
     *   instantiated the specified channel.
     *
     * @param string $id channel ID (e.g. `local`).
     * @param bool $checkInstance whether the method should check if the channel is shared and instantiated.
     * @return bool whether the locator has the specified channel definition or has instantiated the channel.
     * @see set()
     */
    public function has($id, $checkInstance = false)
    {
        return $checkInstance ? isset($this->_channels[$id]) : isset($this->_definitions[$id]);
    }

    /**
     * Returns the channel instance with the specified ID.
     *
     * @param string $id channel ID (e.g. `db`).
     * @param bool $throwException whether to throw an exception if `$id` is not registered with the locator before.
     * @return Channel|object|null the channel of the specified ID. If `$throwException` is false and `$id`
     * is not registered before, null will be returned.
     * @throws InvalidConfigException if `$id` refers to a nonexistent channel ID
     * @see has()
     * @see set()
     */
    public function get($id, $throwException = true)
    {
        if (isset($this->_channels[$id])) {
            return $this->_channels[$id];
        }

        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];
            if (is_object($definition) && !$definition instanceof Closure) {
                return $this->_channels[$id] = $definition;
            }
            return $this->_channels[$id] = Yii::createObject($definition);
        } elseif ($throwException) {
            throw new InvalidConfigException("Unknown channel ID: $id");
        }

        return null;
    }

    /**
     * Registers a filesystem definition with this locator.
     *
     * For example,
     *
     * ```php
     * // a class name
     * $locator->set('cache', 'yii\caching\FileCache');
     *
     * // a configuration array
     * $locator->set('db', [
     *     'class' => 'yii\db\Connection',
     *     'dsn' => 'mysql:host=127.0.0.1;dbname=demo',
     *     'username' => 'root',
     *     'password' => '',
     *     'charset' => 'utf8',
     * ]);
     *
     * // an anonymous function
     * $locator->set('cache', function ($params) {
     *     return new \yii\caching\FileCache;
     * });
     *
     * // an instance
     * $locator->set('cache', new \yii\caching\FileCache);
     * ```
     *
     * If a filesystem definition with the same ID already exists, it will be overwritten.
     *
     * @param string $id filesystem ID (e.g. `db`).
     * @param mixed $definition the filesystem definition to be registered with this locator.
     * It can be one of the following:
     *
     * - a class name
     * - a configuration array: the array contains name-value pairs that will be used to
     *   initialize the property values of the newly created object when [[get()]] is called.
     *   The `class` element is required and stands for the the class of the object to be created.
     * - a PHP callable: either an anonymous function or an array representing a class method (e.g. `['Foo', 'bar']`).
     *   The callable will be called by [[get()]] to return an object associated with the specified filesystem ID.
     * - an object: When [[get()]] is called, this object will be returned.
     *
     * @throws InvalidConfigException if the definition is an invalid configuration array
     */
    public function set($id, $definition)
    {
        unset($this->_channels[$id]);

        if ($definition === null) {
            unset($this->_definitions[$id]);
            return;
        }

        if (is_object($definition) || is_callable($definition, true)) {
            // an object, a class name, or a PHP callable
            $this->_definitions[$id] = $definition;
        } elseif (is_array($definition)) {
            // a configuration array
            if (isset($definition['class'])) {
                $this->_definitions[$id] = $definition;
            } else {
                throw new InvalidConfigException("The configuration for the \"$id\" channel must contain a \"class\" element.");
            }
        } else {
            throw new InvalidConfigException("Unexpected configuration type for the \"$id\" channel: " . gettype($definition));
        }
    }

    /**
     * Removes the channel from the locator.
     * @param string $id the channel ID
     */
    public function clear($id)
    {
        unset($this->_definitions[$id], $this->_channels[$id]);
    }

    /**
     * Returns the list of the channel definitions or the loaded channel instances.
     * @param bool $returnDefinitions whether to return channel definitions instead of the loaded channel instances.
     * @return array the list of the channel definitions or the loaded channel instances (ID => definition or instance).
     */
    public function getChannels($returnDefinitions = true)
    {
        return $returnDefinitions ? $this->_definitions : $this->_channels;
    }

    /**
     * Registers a set of channel definitions in this locator.
     *
     * This is the bulk version of [[set()]]. The parameter should be an array
     * whose keys are channel IDs and values the corresponding channel definitions.
     *
     * For more details on how to specify channel IDs and definitions, please refer to [[set()]].
     *
     * If a channel definition with the same ID already exists, it will be overwritten.
     *
     * The following is an example for registering two channel definitions:
     *
     * ```php
     * [
     *     'local' => [
     *         'class' => 'yuncms\NotificationManager\channels\Local',
     *     ],
     * ]
     * ```
     *
     * @param array $channels channel definitions or instances
     * @throws InvalidConfigException
     */
    public function setChannels($channels)
    {
        foreach ($channels as $id => $channel) {
            $this->set($id, $channel);
        }
    }

    /**
     * 通过可用渠道将给定的通知发送给给定的可通知实体。您可以传递数组以便将多个通知发送给多个收件人。
     *
     * @param NotifiableInterface[]|NotifiableInterface $recipients 可以收到给定通知的收件人。
     * @param NotificationInterface[]|NotificationInterface $notifications 应该交付的通知。
     * @return void
     * @throws InvalidConfigException
     */
    public function send($recipients, $notifications)
    {
        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }
        if (!is_array($notifications)) {
            $notifications = [$notifications];
        }
        foreach ($recipients as $recipient) {
            $channels = array_intersect($recipient->viaChannels(), array_keys($this->getChannels(true)));
            foreach ($notifications as $notification) {
                if (!$recipient->shouldReceiveNotification($notification)) {
                    continue;
                }
                $channels = array_intersect($channels, $notification->broadcastOn());
                foreach ($channels as $channel) {
                    $this->get($channel)->send($recipient, $notification);
                }
            }
        }
    }
}