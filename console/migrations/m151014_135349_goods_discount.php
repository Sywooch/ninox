<?php

use yii\db\Migration;

class m151014_135349_goods_discount extends Migration
{
    public function up()
    {
	    $this->execute("ALTER TABLE `goods`
			MODIFY COLUMN `PriceOut1`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceIn`,
			MODIFY COLUMN `PriceOut2`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceOut1`,
			MODIFY COLUMN `PriceOut3`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceOut2`,
			MODIFY COLUMN `PriceOut4`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceOut3`,
			MODIFY COLUMN `PriceOut5`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceOut4`,
			MODIFY COLUMN `PriceOut6`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceOut5`,
			MODIFY COLUMN `PriceOut7`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceOut6`,
			MODIFY COLUMN `PriceOut8`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceOut7`,
			MODIFY COLUMN `PriceOut9`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceOut8`,
			MODIFY COLUMN `PriceOut10`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceOut9`,
			ADD COLUMN `discountType`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 AFTER `PriceOut10`,
			ADD COLUMN `discountSize`  double(24,2) UNSIGNED NOT NULL DEFAULT 0 AFTER `discountType`;");

	    $this->execute("UPDATE `goods` SET
	    	`discountType` = '1',
	    	`discountSize` = `PriceOut3` - `PriceOut1`,
	    	`PriceOut1` = `PriceOut3`,
	    	`PriceOut2` = 1.5 * `PriceOut3`,
	    	`PriceOut3` = '0' WHERE `PriceOut3` != '0'");
    }

    public function down()
    {
        echo "m151014_135349_goods_update cannot be reverted.\n";
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
