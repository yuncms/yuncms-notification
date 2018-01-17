<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notification\components;

use Yii;
use yii\di\Instance;
use yii\mail\MailerInterface;
use yuncms\user\models\User;

/**
 * Class EmailTarget
 * @package yuncms\notification\components
 */
class MailTarget extends Target
{
    /**
     * @var MailerInterface|array|string the mailer object or the application component ID of the mailer object.
     * After the EmailTarget object is created, if you want to change this property, you should only assign it
     * with a mailer object.
     */
    public $mailer = 'mailer';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\MailerInterface');
    }

    /**
     * Exports notification [[messages]] to a specific destination.
     * Child classes must implement this method.
     */
    public function export()
    {
        if (empty($this->message['subject'])) {
            $this->message['subject'] = Yii::$app->name;
        }

        $user = User::findOne($this->message['to_user_id']);

        $this->mailer->compose()->setTextBody(wordwrap(implode("\n", $this->message), 70))
            ->setSubject($this->message['subject'])
            ->setTo($user->email)
            ->send($this->mailer);
    }
}