<?php

use yii\db\Migration;

class m160504_141414_some_pages extends Migration
{
    public function up()
    {
        \common\models\Category::updateAll(['viewFile' => 'contacts'], ['ID' => '434']);
        \common\models\Category::updateAll(['viewFile' => 'help'], ['ID' => '437']);
    }

    public function down()
    {
        echo "m160504_141414_some_pages cannot be reverted.\n";

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
