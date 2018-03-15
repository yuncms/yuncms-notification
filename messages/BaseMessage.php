<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\messages;

use yii\base\BaseObject;
use yii\base\Model;

/**
 * Class BaseMessage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BaseMessage extends Model
{
    /**
     * The title of the notification.
     * @var string
     */
    public $title;

    /**
     * The notification's message body
     * @var string
     */
    public $body;
}