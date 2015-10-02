<?php

use yii\db\Schema;
use yii\db\Migration;

class m150512_134729_fucking_bool_values_1 extends Migration
{
    public function up()
    {
        $this->db->createCommand("ALTER TABLE `".app\models\Category::tableName()."`
        MODIFY `onePrice` tinyint(1) DEFAULT 0,
        MODIFY `canBuy` tinyint(1) DEFAULT 0,
        MODIFY `hasFilter` tinyint(1) DEFAULT 0")->execute();
    }

    public function down()
    {
        echo "m150512_134729_fucking_bool_values_1 cannot be reverted.\n";

        return true;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
