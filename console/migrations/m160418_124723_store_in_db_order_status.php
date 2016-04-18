<?php

use yii\db\Migration;

class m160418_124723_store_in_db_order_status extends Migration
{
    public function up()
    {
        $this->addColumn('history', 'status', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');

        foreach(\backend\models\History::find()->each() as $order){
            $order->status = $order->getStatus();
            $order->save(false);
        }
    }

    public function down()
    {
        return $this->dropColumn('history', 'status');
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
