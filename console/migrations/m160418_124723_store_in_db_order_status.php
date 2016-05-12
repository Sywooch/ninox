<?php

use yii\db\Migration;

class m160418_124723_store_in_db_order_status extends Migration
{
    public function up()
    {
        $this->addColumn('history', 'status', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
        $this->addColumn('history', 'statusChangedDate', \yii\db\Schema::TYPE_DATETIME);

        $count = \backend\models\History::find()->count();
        $i = 1;
        foreach(\backend\models\History::find(200)->each() as $order){
            $order->status = $order->getCurrentStatus();
            $order->save(false);
            echo "    > {$i} order from {$count} orders\r\n";
            $i++;
        }
    }

    public function down()
    {
        $this->dropColumn('history', 'status');
        $this->dropColumn('history', 'statusChangedDate');
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
