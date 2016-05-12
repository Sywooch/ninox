<?php

use yii\db\Migration;
use yii\db\Schema;

class m151120_090447_GUID_using_in_history_sborka extends Migration
{
	public function modChild($parent){
		if(\backend\models\History::find()->where(['parent_id' => $parent->number])->count() > 0){
			foreach(\backend\models\History::find()->where(['parent_id' => $parent->number])->each(100) as $child){
				$this->modChild($child);
				foreach(\backend\models\SborkaItem::findAll(['orderID' => $child->id]) as $childItem){
					$parentItemFound = false;
					foreach(\backend\models\SborkaItem::findAll(['orderID' => $parent->id]) as $parentItem){
						if($parentItem->itemID == $childItem->itemID){
							echo "{$childItem->itemID} parent found\r\n";
							$parentItem->count += $childItem->count;
							$parentItem->save(false);
							$childItem->delete();
							$parentItemFound = true;
							break;
						}
					}
					if(!$parentItemFound){
						$childItem->orderID = $parent->id;
						$childItem->save(false);
						echo "{$childItem->itemID} parent not found\r\n";
					}
				}
				$child->parent_id = 0;
				$child->save(false);
				echo " childID {$child->id} with number {$child->number} is modified to parentID{$parent->id}\r\n";
			}
		}else{
			$parent->parent_id = 0;
			$parent->save(false);
			echo "has no child\r\n";
		}
	}

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

	    $this->renameColumn('partners', 'PaymentType', 'paymentType');
	    $this->renameColumn('partners', 'ShippingType', 'deliveryType');
	    $this->alterColumn('partners', 'paymentType', Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
	    $this->alterColumn('partners', 'deliveryType', Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
	    $this->renameColumn('partners', 'Discount', 'discount');

	    $withChild = [];

	    foreach(\backend\models\History::find()->where(['>', 'parent_id', 0])->each(100) as $item){
		    $withChild[] = $item->parent_id;
	    }

	    if(!empty($withChild)){
		    $count = \backend\models\History::find()
			    ->where(['or', ['parent_id' => 0], 'parent_id IS NULL'])
			    ->andWhere(['in', 'number', $withChild])
			    ->count();

		    if($count > 0){
			    $i = 1;
			    foreach(\backend\models\History::find()
				            ->where(['or', ['parent_id' => 0], 'parent_id IS NULL'])
				            ->andWhere(['in', 'number', $withChild])->each(100) as $order){
				    echo "Check order {$i} from {$count}: ";
				    $this->modChild($order);
				    $i++;
			    }
		    }
	    }

	    $this->dropColumn('history', 'parent_id');
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
