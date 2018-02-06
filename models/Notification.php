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
use yuncms\notification\Module;
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
            [['seen', 'read'], 'boolean'],
            [['seen', 'read'], 'default', 'value' => false],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('model', 'Id'),
            'user_id' => Module::t('model', 'User Id'),
            'category' => Module::t('model', 'Category'),
            'action' => Module::t('model', 'Action'),
            'message' => Module::t('model', 'Message'),
            'route' => Module::t('model', 'Route'),
            'seen' => Module::t('model', 'Seen'),
            'status' => Module::t('model', 'Status'),
            'created_at' => Module::t('model', 'Created At'),
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
