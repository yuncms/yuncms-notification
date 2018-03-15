<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification;

use yii\base\BaseObject;

/**
 * Class Channel
 * @package yuncms\notification\components
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
abstract class Channel extends BaseObject
{
    /**
     * @var string 渠道ID
     */
    public $id;

    /**
     * Channel constructor.
     * @param string $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $this->id = $id;
        parent::__construct($config);
    }

    /**
     * 开始推送
     * @param Notification $notification
     * @return mixed
     */
    abstract public function send(Notification $notification);
}