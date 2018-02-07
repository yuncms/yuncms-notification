<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yuncms\notification\frontend\models\Notification;

/** @var Notification $model */
/* @var yii\web\View $this */
?>
<li class="notification-item<? if ($model->isRead): ?> read<? endif; ?>" data-id="<?= $model->id ?>" data-action="<?= $model->action ?>">
    <a href="<?= $model->url ?>">
        <span class="icon"></span>
        <span class="message"><?= $model->message; ?></span>
    </a>
    <small class="time-ago"><?= Yii::$app->formatter->asRelativeTime($model->created_at); ?></small>
    <span class="mark-read" data-toggle="tooltip" title="<? if ($model->isRead): ?><?= Yii::t('notification', 'Read') ?><? else: ?><?= Yii::t('notification', 'Mark as read') ?><? endif; ?>"></span>
</li>
