<?php

use yii\helpers\Html;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;
use xutl\inspinia\ActiveForm;

/* @var yii\web\View $this  */
/* @var yuncms\notification\models\Settings $model  */

$this->title = Yii::t('notification', 'Settings');
$this->params['breadcrumbs'][] = Yii::t('notification', 'Manage Authentication');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 authentication-update">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget([
                        'items' => [
                            [
                                'label' => Yii::t('notification', 'Manage Notification'),
                                'url' => ['index'],
                            ],
                            [
                                'label' => Yii::t('notification', 'Settings'),
                                'url' => ['settings'],
                            ],
                        ]
                    ]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal'
            ]); ?>

            <?= $form->field($model, 'enableAppPush')->inline()->checkbox([], false); ?>
            <?= $form->field($model, 'enableEmailPush')->inline()->checkbox([], false); ?>
            <?= $form->field($model, 'enableSmsPush')->inline()->checkbox([], false); ?>

            <?= Html::submitButton(Yii::t('notification', 'Settings'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>