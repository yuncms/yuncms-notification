<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

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
 * @property int $user_id 用户ID
 * @property string $category 类目
 * @property string $action 操作
 * @property string $message 消息内容
 * @property string $route 路由
 * @property bool $seen 是否看过
 * @property bool $read 是否已读
 * @property int $created_at 创建时间
 *
 * @property-read bool $isRead 是否已读
 * @property User $user 用户实例
 */
class Notification extends ActiveRecord
{
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
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
            ],
            'user' => [
                'class' => BlameableBehavior::class,
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
            [['seen', 'read'], 'boolean'],
            [['seen', 'read'], 'default', 'value' => false],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'seen' => Yii::t('notification', 'Seen'),
            'status' => Yii::t('notification', 'Status'),
            'created_at' => Yii::t('notification', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * 是否已读
     * @return bool
     */
    public function getIsRead()
    {
        return (bool)$this->read;
    }

    /**
     * 获取路由
     * @return array|string
     */
    public function getRoute()
    {
        return @unserialize($this->route);
    }

    /**
     * 设置指定用户为全部已读
     * @param int $userId
     * @return int
     */
    public static function setReadAll($userId)
    {
        return self::updateAll(['read' => true], ['user_id' => $userId]);
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
