<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\components;

/**
 * Class EmailTarget
 * @package yuncms\notification\components
 */
class EmailTarget extends Target
{
    public $title = 'Email';

    /**
     * Exports notification [[messages]] to a specific destination.
     * Child classes must implement this method.
     */
    public function export($messages)
    {
        // TODO: Implement export() method.
    }
}