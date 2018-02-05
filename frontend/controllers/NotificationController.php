<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\frontend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yuncms\notification\frontend\models\Notification;

/**
 * Class NotificationController
 * @package yuncms\notification
 */
class NotificationController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'read-all' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'read-all'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['unread-notifications'],
                        'roles' => ['@', '?']
                    ],
                ],
            ],
        ];
    }

    /**
     * 显示通知首页
     * @return string
     */
    public function actionIndex()
    {
        $query = Notification::find()->where(['user_id' => Yii::$app->user->id, 'channel' => 'screen'])->orderBy(['id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * 标记通知为已读
     * @return \yii\web\Response
     */
    public function actionReadAll()
    {
        Notification::setReadAll(Yii::$app->user->id, 'screen');
        Yii::$app->session->setFlash('success', Yii::t('notification', 'Successful operation.'));
        return $this->redirect(['index']);
    }

    /**
     * 未读通知数目
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionUnreadNotifications()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->user->isGuest) {
            $total = 0;
        } else {
            $total = Notification::getDb()->cache(function ($db) {
                return Notification::find()->where(['user_id' => Yii::$app->user->id, 'channel' => 'screen', 'status' => Notification::STATUS_UNREAD])->count();
            }, 60);
        }
        return ['total' => $total];
    }
}