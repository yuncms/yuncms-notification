<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\backend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yuncms\notification\models\Notification;

/**
 * Class NotificationController
 * @package yuncms\notification
 */
class NotificationController extends Controller
{

    public function actions()
    {
        return [
            //....
            'settings' => [
                'class' => 'yuncms\core\actions\SettingsAction',
                'modelClass' => 'yuncms\notification\models\Settings',
                //'scenario' => 'user',
                //'scenario' => 'site', // Change if you want to re-use the model for multiple setting form.
                'viewName' => 'settings'    // The form we need to render
            ],
            //....
        ];
    }

}