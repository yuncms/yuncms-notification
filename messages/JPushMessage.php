<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\messages;

/**
 * 移动终端消息
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class JPushMessage extends BaseMessage
{
    /**
     * @var array 扩展参数
     */
    public $extParameters;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'titleRequired' => ['title', 'required'],
            'bodyRequired' => ['body', 'required'],
        ];
    }
}