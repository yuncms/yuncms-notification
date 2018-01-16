<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yuncms\user\models\User;

/**
 * Notice model 通知模型
 *
 * @property integer $id
 * @property integer $user_id 发送方ID，系统消费为空
 * @property integer $to_user_id 接收方ID
 * @property string $type 通知类型代码
 * @property string $subject 资源标题
 * @property integer $model_id 资源ID
 * @property string $refer_model 引用模型名称
 * @property string $refer_model_id 引用模型ID
 * @property string $content 通知内容
 * @property integer $status 状态
 * @property integer $created_at 创建时间
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
     * 定义行为
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
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_UNREAD],
            ['status', 'in', 'range' => [self::STATUS_READ, self::STATUS_UNREAD]],
        ];
    }

    /**
     * 发送者用户实例
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * 接受者用户实例
     * @return \yii\db\ActiveQuery
     */
    public function getToUser()
    {
        return $this->hasOne(User::className(), ['id' => 'to_user_id']);
    }

    /**
     * 设置指定用户为全部已读
     * @param int $toUserId
     * @return int
     */
    public static function setReadAll($toUserId)
    {
        return self::updateAll(['status' => self::STATUS_READ], ['to_user_id' => $toUserId]);
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
}