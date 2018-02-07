<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\notification\models;

use Yii;
use yii\base\Model;

/**
 * 通知后台设置
 * @package yuncms\notification\models
 */
class Settings extends Model
{
    /**
     * @var boolean 是否APP推送
     */
    public $enableAppPush;

    /**
     * @var boolean 是否开启邮件推送
     */
    public $enableEmailPush;

    /**
     * @var integer 是否开启短信推送
     */
    public $enableSmsPush;

    /**
     * 定义字段类型
     * @return array
     */
    public function getTypes()
    {
        return [
            'enableAppPush' => 'boolean',
            'enableEmailPush' => 'boolean',
            'enableSmsPush' => 'boolean',
        ];
    }

    public function rules()
    {
        return [
            [['enableAppPush','enableEmailPush','enableSmsPush'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'enableAppPush' => Yii::t('notification', 'Enable App Push'),
            'enableEmailPush' => Yii::t('notification', 'Enable Email Push'),
            'enableSmsPush' => Yii::t('notification', 'Enable Sms Push'),
        ];
    }

    /**
     * 返回标识
     */
    public function formName()
    {
        return 'notification';
    }
}