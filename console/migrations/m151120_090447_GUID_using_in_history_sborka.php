<?php

use yii\db\Migration;
use yii\db\Schema;

class m151120_090447_GUID_using_in_history_sborka extends Migration
{
    public function up()
    {
	    $this->renameColumn('history', 'id', 'number');
	    $this->alterColumn('history', 'number', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->addColumn('history', 'id', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0 FIRST');
	    $this->alterColumn('sborka', 'orderID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0 FIRST');
	    $this->alterColumn('sborka', 'itemID', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');

	    foreach(\common\models\History::find()->each(100) as $order){
		    $order->id = hexdec(uniqid());
		    if($order->save(false)){
			    \common\models\SborkaItem::updateAll(['orderID' => $order->id], ['orderID' => $order->number]);
		    }
	    }

	    \common\models\SborkaItem::deleteAll('LENGTH(`orderID`) < 6');

	    $this->dropPrimaryKey('number', 'history');
	    $this->addPrimaryKey('id', 'history', 'id');
	    $this->execute("UPDATE `sborka` as `t`,
			(
			    SELECT `orderID`, `itemID`, SUM(`count`) AS `sc`, SUM(`originalCount`) AS `soc` FROM `sborka` GROUP BY `orderID`, `itemID` HAVING COUNT(`count`) > 1
			) as `temp`
			SET `t`.`count` = `temp`.`sc`, `t`.`originalCount` = `temp`.`soc` WHERE `t`.`orderID` = `temp`.`orderID` AND `t`.`itemID` = `temp`.`itemID`");
	    $this->execute("DELETE s1 FROM `sborka` s1, `sborka` s2 WHERE s1.id > s2.id AND s1.orderID = s2.orderID AND s1.itemID = s2.itemID");
	    $this->dropColumn('sborka', 'id');
	    $this->addPrimaryKey('id', 'sborka', ['orderID', 'itemID']);
    }

    public function down()
    {
        echo "m151120_090447_GUID_using_in_history_sborka_users cannot be reverted.\n";
        return false;
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
