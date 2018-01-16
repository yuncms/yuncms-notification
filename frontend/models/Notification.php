<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\frontend\models;

use Yii;

class Notification extends \yuncms\notification\models\Notification
{
    /**
     * 获取类型字符
     * @return mixed|null
     */
    public function getTypeText()
    {
        switch ($this->type) {
            case 'follow_user':
                return Yii::t('notification', 'follow on you');
                break;
            case 'answer_question':
                return Yii::t('notification', 'answered the question');
                break;
            case 'follow_question':
                return Yii::t('notification', 'is concerned about the problem');
                break;
            case 'comment_question':
                return Yii::t('notification', 'commented on the question');
                break;
            case 'invite_answer':
                return Yii::t('notification', 'invited you to answer');
                break;
            case 'adopt_answer':
                return Yii::t('notification', 'accepted your answer');
                break;
            default:
                return null;
                break;
        }
    }
}