<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yuncms\notification\frontend\models\Notification;
use yuncms\notification\frontend\Module;

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
                    'delete-all' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'list', 'count', 'read-all', 'delete-all'],
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
        $query = Notification::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * AJAX获取通知列表
     */
    public function actionList()
    {
        $notifications = Notification::find()->andWhere(['in', 'user_id', [0, Yii::$app->user->id]])->limit(10)->orderBy(['id' => SORT_DESC])->all();
        $lists = $this->prepareNotifications($notifications);
        $this->ajaxResponse(['list' => $lists]);
    }

    /**
     * 获取未读通知总数
     */
    public function actionCount()
    {
        $count = Notification::getCountUnseen();
        $this->ajaxResponse(['count' => 500]);
    }

    /**
     * 标记为已读
     * @param int $id
     * @return Response
     */
    public function actionRead($id)
    {
        Notification::updateAll(['read' => true], ['id' => $id]);
        return $this->ajaxResponse(1);
    }

    /**
     * 标记通知为已读
     * @return Response
     */
    public function actionReadAll()
    {
        Notification::setReadAll(Yii::$app->user->id);
        if (Yii::$app->getRequest()->getIsAjax()) {
            return $this->ajaxResponse(1);
        }
        Yii::$app->getSession()->setFlash('success', Module::t('frontend', 'All notifications have been marked as read.'));
        return $this->redirect(['index']);
    }

    /**
     * 删除所有通知
     * @return Response
     */
    public function actionDeleteAll()
    {
        Notification::deleteAll(['user_id' => Yii::$app->user->id]);
        if (Yii::$app->getRequest()->getIsAjax()) {
            return $this->ajaxResponse(1);
        }
        Yii::$app->getSession()->setFlash('success', Module::t('frontend', 'All notifications have been deleted.'));
        return $this->redirect(['index']);
    }

    /**
     * 预处理通知
     * @param Notification[] $notifications
     * @return array
     */
    private function prepareNotifications($notifications)
    {
        $notifies = [];
        $seen = [];
        foreach ($notifications as $notification) {
            if (!$notification->seen) {
                $seen[] = $notification->id;
            }
            $notify = $notification->toArray();
            $notify['url'] = $notification->url;
            $notify['relativeTime'] = $notification->relativeTime;
            $notifies[] = $notify;
        }

        if (!empty($seen)) {
            Notification::updateAll(['seen' => 1], ['in', 'id', $seen]);
        }

        return $notifies;
    }

    /**
     * AJAX响应
     * @param array $data
     * @return Response
     */
    public function ajaxResponse($data = [])
    {
        if (is_string($data)) {
            $data = ['html' => $data];
        }
        $flashes = Yii::$app->getSession()->getAllFlashes(true);
        foreach ($flashes as $type => $message) {
            $data['notifications'][] = [
                'type' => $type,
                'message' => $message,
            ];
        }
        return $this->asJson($data);
    }
}