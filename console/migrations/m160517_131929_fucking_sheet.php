<?php

/**
 * делал пиздец голодным
 * с плохим настроением
 * за работоспособность не ручаюсь
 */
use yii\db\Migration;

class m160517_131929_fucking_sheet extends Migration
{
    public function up()
    {
        $this->execute("UPDATE `history`, `send_pay` SET `send_pay`.`read_confirm` = '1' WHERE `history`.`number` = CAST(`send_pay`.`nomer_id` as UNSIGNED) AND `send_pay`.`read_confirm` = 0 AND `history`.`moneyConfirmed` = '1'");
    }

    public function down()
    {
        echo "m160517_131929_fucking_sheet cannot be reverted.\n";
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
