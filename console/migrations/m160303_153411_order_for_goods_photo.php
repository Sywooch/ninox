<?php

use yii\db\Migration;

class m160303_153411_order_for_goods_photo extends Migration
{
    public function up()
    {
        $itemsCount = \common\models\GoodsPhoto::find()->count();

        $i = 0;

        echo '     > try to order additionalPhotos for goods...';
        echo "\r\n     > We have {$itemsCount} items...";

        foreach(\common\models\GoodsPhoto::find()->each() as $good){
            $good->order = \common\models\GoodsPhoto::find()
                    ->select('MAX(`order`)')
                    ->where(['itemID' => $good->itemid])
                    ->scalar();

            $good->order++;

            $i++;

            if($good->save(false)){
                echo "\r\n     > Saving item {$i} from {$itemsCount}...";
            }
        }
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
