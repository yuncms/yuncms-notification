<?php

namespace yuncms\notification\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%notification_settings}}".
 *
 * @property int $user_id User Id
 * @property string $category Category
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
    const ENABLED_MSG = 'msg';
    const ENABLED_SMS = 'sms';
    const ENABLED_EMAIL = 'email';
    const ENABLED_APP = 'app';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification_settings}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category'], 'required'],
            [['user_id'], 'integer'],
            [['category'], 'string', 'max' => 200],
            [['msg', 'email', 'sms', 'app'], 'boolean'],
            [['msg', 'email', 'sms', 'app'], 'default', 'value' => true],
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

    public function getPrimaryKey($asArray = false)
    {
        return parent::getPrimaryKey($asArray);
    }

    /**
     * 获取用户消息设置
     * @param integer $user_id
     * @param string $category
     * @return static
     */
    public static function getSettings($user_id, $category)
    {
        if (($model = static::findOne(['user_id' => $user_id, 'category' => $category])) != null) {
            return $model;
        } else {
            return static::create(['user_id' => $user_id, 'category' => $category]);
        }
    }

    /**
     * 设置用户通知设置
     * @param integer $user_id
     * @param string $category
     * @param array $settings
     * @return bool
     */
    public static function setSettings($user_id,$category, $settings)
    {
        $model = static::getSettings($user_id, $category);
        $model->setAttributes($settings);
        return $model->save();
    }

    /**
     * 快速创建实例
     * @param array $attributes
     * @param boolean $runValidation
     * @return bool|NotificationSettings
     */
    public static function create(array $attributes, $runValidation = true)
    {
        $model = new static ($attributes);
        if ($model->save($runValidation)) {
            return $model;
        }
        return false;
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
