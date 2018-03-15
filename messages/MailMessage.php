<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\messages;

/**
 * Class MailMessage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class MailMessage extends BaseMessage
{
    /**
     * The view to be used for rendering the message body.
     * @var string|array|null $view
     */
    public $view;

    /**
     * The parameters (name-value pairs) that will be extracted and made available in the view file.
     * @var array
     */
    public $viewData;

    /**
     * The message sender.
     * @var string
     */
    public $from;
}