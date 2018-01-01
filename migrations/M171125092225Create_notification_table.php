<?php

namespace yuncms\notification\migrations;

use yii\db\Migration;

class M171125092225Create_notification_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey()->unsigned()->comment('Id'),
            'user_id' => $this->integer()->unsigned()->comment('User Id'),
            'to_user_id' => $this->integer()->unsigned()->comment('To User Id'),
            'type' => $this->string()->comment('type'),
            'subject' => $this->string()->comment('subject'),
            'model_id' => $this->integer()->comment('Model Id'),
            'refer_model' => $this->string()->comment('Refer Model'),
            'refer_model_id' => $this->integer()->comment('Refer Model Id'),
            'content' => $this->string()->comment('Content'),
            'status' => $this->smallInteger(1)->comment('Status'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
        ], $tableOptions);

        $this->addForeignKey('{{%notification_fk_1}}', '{{%notification}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%notification_fk_2}}', '{{%notification}}', 'to_user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    public function safeDown()
    {
        $this->dropTable('{{%notification}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171125092225Create_notification_table cannot be reverted.\n";

        return false;
    }
    */
}
