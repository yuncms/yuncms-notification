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
use yuncms\notification\Module;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%notification_settings}}".
 *
 * @property int $user_id User Id
 * @property string $category Category
 * @property int $screen ScreenChannel
 * @property int $email EmailChannel
 * @property int $sms SmsChannel
 * @property int $app AppChannel
 * @property int $updated_at Updated At
 *
 * @property User $user
 */
class NotificationSettings extends ActiveRecord
{
    const ENABLED_SCREEN = 'screen';
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
            TimestampBehavior::className(),
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
            [['screen', 'email', 'sms', 'app'], 'boolean'],
            [['screen', 'email', 'sms', 'app'], 'default', 'value' => true],
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
            'screen' => Module::t('model', 'ScreenChannel'),
            'email' => Module::t('model', 'EmailChannel'),
            'sms' => Module::t('model', 'SmsChannel'),
            'app' => Module::t('model', 'AppChannel'),
            'updated_at' => Module::t('model', 'Updated At'),
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
     * @param string $category 类别
     * @param array $settings
     * @return bool
     */
    public static function setSettings($user_id, $category, $settings)
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
