<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yuncms\notification\frontend\assets\NotificationAsset;
use yuncms\notification\frontend\models\Notification;

/**
 * Class Notifications
 * @package yuncms\notification\frontend\widgets
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class NotificationWidget extends Widget
{

    public $options = ['class' => 'dropdown nav-notifications'];

    /**
     * @var array additional options to be passed to the notification library.
     * Please refer to the plugin project page for available options.
     */
    public $clientOptions = [];

    /**
     * @var integer the XHR timeout in milliseconds
     */
    public $xhrTimeout = 2000;

    /**
     * @var integer The delay between pulls in milliseconds
     */
    public $pollInterval = 60000;

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $html = Html::beginTag('li', $this->options);
        $html .= Html::beginTag('a', ['href' => '#', 'id' => 'unread_notifications', 'data-toggle' => 'dropdown']);

        $count = Notification::getCountUnseen();

        $html .= Html::tag('span', '', ['class' => 'fa fa-bell-o fa-lg']);

        if ($count > 0) {
            if ($count > 99) $count = '99+';
            $html .= Html::tag('span', $count, ['id' => 'notifications-count', 'class' => 'label label-danger']);
        }

        $html .= Html::endTag('a');

        $html .= Html::begintag('div', ['class' => 'dropdown-menu']);
        $header = Html::a(Yii::t('notification', 'Mark all as read'), '#', ['class' => 'read-all pull-right']);
        $header .= Yii::t('notification', 'Notifications');
        $html .= Html::tag('div', $header, ['class' => 'header']);
        $html .= Html::begintag('div', ['class' => 'notifications-list']);
        //$html .= Html::tag('div', '<span class="ajax-loader"></span>', ['class' => 'loading-row']);
        $html .= Html::tag('div', Html::tag('span', Yii::t('notification', 'There are no notifications to show'), ['style' => 'display: none;']), ['class' => 'empty-row']);
        $html .= Html::endTag('div');
        $footer = Html::a(Yii::t('notification', 'View all'), ['/notification/notification/index']);
        $html .= Html::tag('div', $footer, ['class' => 'footer']);
        $html .= Html::endTag('div');
        $html .= Html::endTag('li');

        echo $html;

        $this->registerAssets();
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $this->clientOptions = array_merge([
            'id' => $this->options['id'],
            'url' => Url::to(['/notification/notification/list']),
            'countUrl' => Url::to(['/notification/notification/count']),
            'readUrl' => Url::to(['/notifications/notification/read']),
            'readAllUrl' => Url::to(['/notification/notification/read-all']),
            'xhrTimeout' => Html::encode($this->xhrTimeout),
            'pollInterval' => Html::encode($this->pollInterval),
        ], $this->clientOptions);
        $js = 'Notifications(' . Json::encode($this->clientOptions) . ');';
        $view = $this->getView();
        NotificationAsset::register($view);
        $view->registerJs($js);
    }
}
