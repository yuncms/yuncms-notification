<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification;

use Yii;

/**
 * Class Module
 * @package yuncms\notification
 */
class Module extends \yii\base\Module
{
    /**
     * 初始化
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * 注册语言包
     * @return void
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['notification*'])) {
            Yii::$app->i18n->translations['notification*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }
}
