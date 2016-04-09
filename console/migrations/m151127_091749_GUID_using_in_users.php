<?php

use yii\db\Migration;
use yii\db\Schema;

class m151127_091749_GUID_using_in_users extends Migration
{
    public function up()
    {
	    $this->alterColumn('partners', 'ID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->renameColumn('cart', 'userID', 'customerID');
	    $this->alterColumn('cart', 'customerID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0 FIRST');
	    $this->alterColumn('cart', 'cartCode', 'varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `customerID`');
	    $this->renameColumn('cart', 'goodId', 'itemID');
	    $this->alterColumn('cart', 'itemID', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0 AFTER `cartCode`');
	    \common\models\Cart::deleteAll('`count` = 0');
	    \common\models\Cart::deleteAll('`cartCode` = \'\'');
	    $this->execute("UPDATE `cart` as `t`,
			(
			    SELECT `cartCode`, `itemID`, SUM(`count`) AS `sc` FROM `cart` GROUP BY `cartCode`, `itemID` HAVING COUNT(`count`) > 1
			) as `temp`
			SET `t`.`count` = `temp`.`sc` WHERE `t`.`cartCode` = `temp`.`cartCode` AND `t`.`itemID` = `temp`.`itemID`");
	    $this->execute("DELETE c1 FROM `cart` c1, `cart` c2 WHERE c1.id > c2.id AND c1.cartCode = c2.cartCode AND c1.itemID = c2.itemID");
	    $this->dropColumn('cart', 'id');
	    $this->dropColumn('cart', 'good');
	    $this->addPrimaryKey('customerID', 'cart', ['customerID', 'cartCode', 'itemID']);
	    $this->renameColumn('goodscomments', 'userid', 'customerID');
	    $this->alterColumn('goodscomments', 'customerID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0');
		$this->alterColumn('history', 'customerID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->renameColumn('handmade_partners', 'partnerID', 'customerID');
	    $this->alterColumn('handmade_partners', 'customerID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->renameColumn('handmade_goods', 'partnerID', 'customerID');
	    $this->alterColumn('handmade_goods', 'customerID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->renameColumn('handmade_services', 'partnerID', 'customerID');
	    $this->alterColumn('handmade_services', 'customerID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->renameColumn('handmade_transactions', 'partner_id', 'customerID');
	    $this->alterColumn('handmade_transactions', 'customerID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0');
	    echo "    > drop trigger `InsertRate`...\r\n";
	    $this->execute("DROP TRIGGER IF EXISTS `InsertRate`");
	    echo "    > drop trigger `UpdateRate`...\r\n";
	    $this->execute("DROP TRIGGER IF EXISTS `UpdateRate`");
	    $this->renameColumn('items_rate', 'userID', 'customerID');
	    $this->alterColumn('items_rate', 'customerID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->renameColumn('social_profiles', 'partner_id', 'customerID');
	    $this->alterColumn('social_profiles', 'customerID', Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->renameTable('users_pricerules', 'partnersPricerules');
	    $this->renameColumn('partnersPricerules', 'UserGUID', 'customerID');
	    $this->createIndex('customerID', 'partnersPricerules', 'customerID');
	    foreach(\common\models\oldCustomer::find()->each(100) as $customer){
		    $customer->Code = $customer->ID;
		    $customer->ID = hexdec(uniqid());
		    if($customer->save(false)){
				echo "    > update history table for {$customer->Code}... \r\n";
			    \common\models\History::updateAll(['customerID' => $customer->ID, 'customerEmail' => $customer->eMail], ['customerID' => $customer->Code]);
				echo "    > update cart table for {$customer->Code}...\r\n";
			    \common\models\Cart::updateAll(['customerID' => $customer->ID], ['customerID' => $customer->Code]);
				echo "    > update GoodsComment table for {$customer->Code}...\r\n";
			    \common\models\GoodsComment::updateAll(['customerID' => $customer->ID], ['customerID' => $customer->Code]);
				echo "    > update HandmadeCustomer table for {$customer->Code}...\r\n";
			    \common\models\HandmadeCustomer::updateAll(['customerID' => $customer->ID], ['customerID' => $customer->Code]);
				echo "    > updateHandmadeItem table for {$customer->Code}...\r\n";
			    \common\models\HandmadeItem::updateAll(['customerID' => $customer->ID], ['customerID' => $customer->Code]);
				echo "    > update HandmadeService table for {$customer->Code}...\r\n";
			    \common\models\HandmadeService::updateAll(['customerID' => $customer->ID], ['customerID' => $customer->Code]);
				echo "    > update HandmadeTransaction table for {$customer->Code}...\r\n";
			    \common\models\HandmadeTransaction::updateAll(['customerID' => $customer->ID], ['customerID' => $customer->Code]);
				echo "    > update ItemRate table for {$customer->Code}...\r\n";
				\common\models\ItemRate::updateAll(['customerID' => $customer->ID], ['customerID' => $customer->Code]);
				echo "    > update SocialProfile table for {$customer->Code}...\r\n";
				\common\models\SocialProfile::updateAll(['customerID' => $customer->ID], ['customerID' => $customer->Code]);
		    }
	    }
    }

    public function down()
    {
        echo "m151127_091749_GUID_using_in_users cannot be reverted.\n";
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
