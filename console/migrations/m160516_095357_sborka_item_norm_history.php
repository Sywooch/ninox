<?php

use yii\db\Migration;

class m160516_095357_sborka_item_norm_history extends Migration
{
    public function up()
    {
        $this->dropPrimaryKey('PRIMARY', \common\models\SborkaItem::tableName());
        $this->addColumn(\common\models\SborkaItem::tableName(), 'ID', \yii\db\Schema::TYPE_BIGINT.' NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $this->execute("UPDATE `tbl_audit_trail` `a`, `sborka` `b` SET `a`.`model_id` = `b`.`ID` WHERE `a`.`model` LIKE '%SborkaItem%' AND `a`.`model_id` = CONCAT('{\"orderID\":\"', `b`.`orderID`, '\",\"itemID\":', `b`.`itemID`, '}')");
    }

    public function down()
    {
        echo "m160516_095357_sborka_item_norm_history cannot be reverted.\n";
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
