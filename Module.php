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
        Yii::$app->i18n->translations['modules/notification/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@yuncms/notification/messages',
            'fileMap' => [
                'modules/notification/validation' => 'validation.php',
                'modules/notification/form' => 'form.php',
                'modules/notification/backend' => 'backend.php',
                'modules/notification/frontend' => 'frontend.php',
                'modules/notification/model' => 'frontend.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/notification/' . $category, $message, $params, $language);
    }
}
