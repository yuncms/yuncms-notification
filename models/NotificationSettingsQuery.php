<?php

namespace yuncms\notification\models;

/**
 * This is the ActiveQuery class for [[NotificationSettings]].
 *
 * @see NotificationSettings
 */
class NotificationSettingsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return NotificationSettings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return NotificationSettings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
