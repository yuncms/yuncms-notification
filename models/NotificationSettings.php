<?php

namespace yuncms\notification\models;

use Yii;
use yii\db\ActiveRecord;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%notification_settings}}".
 *
 * @property string $id Id
 * @property int $user_id User Id
 * @property int $msg Msg
 * @property int $email Email
 * @property int $sms Sms
 * @property int $app App
 * @property int $updated_at Updated At
 *
 * @property User $user
 */
class NotificationSettings extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification_settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'msg', 'email', 'sms', 'app'], 'integer'],
            [['updated_at'], 'required'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('notification', 'Id'),
            'user_id' => Yii::t('notification', 'User Id'),
            'msg' => Yii::t('notification', 'Msg'),
            'email' => Yii::t('notification', 'Email'),
            'sms' => Yii::t('notification', 'Sms'),
            'app' => Yii::t('notification', 'App'),
            'updated_at' => Yii::t('notification', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return NotificationSettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotificationSettingsQuery(get_called_class());
    }
}
