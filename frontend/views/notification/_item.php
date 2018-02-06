<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yuncms\notification\models\Notification;

/** @var Notification $model */
?>
<!--<section class="stream-list-item --><? //= $model->status == Notification::STATUS_UNREAD ? 'not_read' : ''; ?><!--">-->
<!--    <a href="--><? //= Url::to(['/user/space/view', 'id' => $model->user_id]) ?><!--">--><? //= $model->user->nickname ?><!--</a> --><? //= $model->typeText; ?>
<!--    --><?php //if (in_array($model->type, ['answer_question', 'follow_question', 'comment_question', 'comment_answer', 'comment_user', 'question_at'])) {
//        echo Html::a($model->subject, ['/question/question/view', 'id' => $model->model_id], ['target' => '_blank']);
//        if ($model->type == 'comment_answer') {
//            echo Yii::t('notification', 'in your answer');
//        } else if ($model->type == 'comment_user') {
//            echo Yii::t('notification', 'in your comment');
//        } else if ($model->type == 'question_at') {
//            echo Yii::t('notification', 'mentions you');
//        }
//    } else if (in_array($model->type, ['comment_article', 'comment_user'])) {
//        echo Html::a($model->subject, ['/article/view', 'id' => $model->model_id], ['target' => '_blank']);
//        if ($model->type == 'comment_user') {
//            echo Yii::t('notification', 'in your comment');
//        }
//    } ?>
<!--    <span class="text-muted ml-10">--><? //= Yii::$app->formatter->asRelativeTime($model->created_at); ?><!--</span>-->
<!--    --><? //= !empty($model->refer_content) ? Html::tag('blockquote', strip_tags($model->refer_content), ['class' => 'text-fmt']) : ''; ?>
<!--</section>-->


<li class="notification-item<? if ($model->isRead): ?> read<? endif; ?>" data-id="<?= $model->id ?>"
    data-action="<?= $model->action ?>">
    <a href="<?= $model->url ?>">
        <span class="icon"></span>
        <span class="message"><?= $model->message; ?></span>
    </a>
    <small class="time-ago"><?= Yii::$app->formatter->asRelativeTime($model->created_at); ?></small>
    <span class="mark-read" data-toggle="tooltip" title="
        <? if ($model->isRead): ?>

        <?= Yii::t('notification', 'Read') ?><? else: ?><?= Yii::t('notification', 'Mark as read') ?>

<? endif; ?>
">

        </span>

</li>
