<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\frontend\assets;

use yii\web\AssetBundle;

/**
 * Class AttentionAsset
 * @package yuncms\notification\frontend\assets
 */
class NotificationAsset extends AssetBundle
{
    public $sourcePath = '@yuncms/notification/frontend/views/assets';

    public $js = [
        'js/notification.js',
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'css/notification.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}