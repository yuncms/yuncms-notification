<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yuncms\notification\frontend\widgets\NotificationWidget;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('notification', 'Notifications');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <div class="col-xs-12 col-md-12 main">
        <h2 class="h3 profile-title">
            <?= Yii::t('notification', 'Notifications') ?>
            <div class="pull-right">
                <a class="btn btn-primary" href="<?=Url::to(['/notification/notification/read-all'])?>" data-method="post">
                    <?=Yii::t('notification', 'All marked as read')?>
                </a>
            </div>
        </h2>
        <div class="row">
            <div class="col-md-12">
                <?php try {
                    echo ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemOptions' => ['tag' => 'li', 'class' => 'media'],
                        'itemView' => '_item',//子视图
                        'layout' => "{items}\n{pager}",
                        'options' => [
                            'tag' => 'ul',
                            'id' => 'notifications-items'
                        ]
                    ]);
                } catch (Exception $e) {
                } ?>
            </div>
        </div>
    </div>
</div>