<?php

namespace yuncms\notification\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property string $id Id
 * @property int $user_id User Id
 * @property int $to_user_id To User Id
 * @property string $category Category
 * @property string $subject subject
 * @property int $model_id Model Id
 * @property string $refer_model Refer Model
 * @property int $refer_model_id Refer Model Id
 * @property string $content Content
 * @property int $status Status
 * @property int $created_at Created At
 *
 * @property User $user
 * @property User $toUser
 */
class Notification extends ActiveRecord
{
    //未读
    const STATUS_UNREAD = 0b0;

    //已读
    const STATUS_READ = 0b1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * 行为
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
            ],
            'user' => [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    Model::EVENT_BEFORE_VALIDATE => 'user_id',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['category',], 'string', 'max' => 64],
            [['action',], 'string', 'max' => 32],
            [['message', 'route'], 'string', 'max' => 255],
            ['status', 'default', 'value' => self::STATUS_UNREAD],
            ['status', 'in', 'range' => [self::STATUS_READ, self::STATUS_UNREAD]],
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
            'category' => Yii::t('notification', 'Category'),
            'action' => Yii::t('notification', 'Action'),
            'message' => Yii::t('notification', 'Message'),
            'route' => Yii::t('notification', 'Route'),
            'status' => Yii::t('notification', 'Status'),
            'created_at' => Yii::t('notification', 'Created At'),
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
     * 设置指定用户为全部已读
     * @param int $userId
     * @param string $channel 渠道
     * @return int
     */
    public static function setReadAll($userId)
    {
        return self::updateAll(['status' => self::STATUS_READ], ['user_id' => $userId]);
    }

    /**
     * 快速创建实例
     * @param array $attributes
     * @param boolean $runValidation
     * @return mixed
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
     * @return NotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotificationQuery(get_called_class());
    }
}
