<?php

use yii\db\Migration;

class m160518_065941_update_order_status extends Migration
{
    public function up()
    {
        common\models\History::updateAll([
            'confirmed'         =>  1,
            'callback'          =>   1,
            'status'            =>  5,
            'statusChangedDate' =>  '2016-05-18 10:00:00'
        ], "(`paymentType` = '2' OR `paymentType` = '1') AND (`deliveryType` = '1' OR `deliveryType` = '2') AND `deleted` = '0' AND `moneyConfirmed` = '1' AND `status` <= '3' AND `nakladna` NOT LIKE '%-'");

        common\models\History::updateAll([
            'confirmed'         =>  1,
            'callback'          =>   1,
            'status'            =>  5,
            'statusChangedDate' =>  '2016-05-18 10:00:00'
        ], "(`paymentType` = '1' OR `paymentType` = '3' OR (`paymentType` = '2' AND `paymentParam` != '1')) AND (`deliveryType` = '1' OR `deliveryType` = '2') AND `deleted` = '0' AND `moneyConfirmed` = '1' AND `status` <= '3'");
    }

    public function down()
    {
        echo "m160518_065941_update_order_status cannot be reverted.\n";

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
