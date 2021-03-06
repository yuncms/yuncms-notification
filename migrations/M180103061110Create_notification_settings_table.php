<?php

namespace yuncms\notification\migrations;

use yii\db\Migration;

class M180103061110Create_notification_settings_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%notification_settings}}', [
            'user_id' => $this->integer()->unsigned()->notNull()->comment('User Id'),
            'category' => $this->string(200)->notNull()->comment('Category'),
            'screen' => $this->boolean()->defaultValue(true)->comment('Screen'),
            'email' => $this->boolean()->defaultValue(true)->comment('Email'),
            'sms' => $this->boolean()->defaultValue(true)->comment('Sms'),
            'app' => $this->boolean()->defaultValue(true)->comment('App'),
            'updated_at' => $this->integer(10)->unsigned()->notNull()->comment('Updated At'),
        ], $tableOptions);
        $this->addPrimaryKey('notification_settings_pk', '{{%notification_settings}}', 'user_id');
        $this->createIndex('notification_settings_index', '{{%notification_settings}}', ['user_id', 'category'], true);
        $this->addForeignKey('notification_settings_fk_1', '{{%notification_settings}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%notification_settings}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M180103061110Create_notification_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
