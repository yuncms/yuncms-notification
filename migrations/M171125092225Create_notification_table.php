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
            'id' => $this->bigPrimaryKey()->unsigned()->comment('Id'),
            'user_id' => $this->integer()->unsigned()->comment('User Id'),
            'category' => $this->string(64)->notNull()->comment('Category'),
            'action' => $this->string(32)->comment('Action'),
            'message' => $this->string(255)->comment('Message'),
            'route' => $this->string(255)->comment('Route'),
            'status' => $this->smallInteger(1)->comment('Status'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
        ], $tableOptions);

        $this->createIndex('notification_index', '{{%notification}}', ['user_id', 'channel', 'status']);
        $this->addForeignKey('{{%notification_fk_1}}', '{{%notification}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
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
