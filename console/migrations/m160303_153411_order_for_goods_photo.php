<?php

use yii\db\Migration;

class m160303_153411_order_for_goods_photo extends Migration
{
    public function up()
    {
        $itemsCount = \common\models\GoodsPhoto::find()->count();

        echo "     > try to order additionalPhotos for goods...\r\n";
        echo "     > We have {$itemsCount} items...\r\n";
        $this->execute("SET @x = 0, @i = 0;
        UPDATE `dopfoto` SET `order` = IF(`itemid` = @i, (@x:=@x+1), (@x:=1)), `itemid` = (@i:=`itemid`) ORDER BY `itemid` ASC, `order` DESC;");
        echo "     > All items was successfully updated...\r\n";
    }

    public function down()
    {
        echo "m160303_153411_order_for_goods_photo cannot be reverted.\n";
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
