<?php

use common\models\Good;
use common\models\History;
use yii\db\Migration;

class m160517_133403_update_order_source extends Migration
{
    public function up()
    {
        History::updateAll(['orderSource' => 1], ['sourceType' => 0, 'orderSource' => 2]);

        $items = [];
        $itemsCount = Good::find()->count();
        $i = 0;
        $all = 0;
        echo "    > prepare array for batch insert into shopsGoods for ".$itemsCount." items... \r \n";

        foreach(Good::find()->each(100) as $model){
            $model->count = !empty($model->count) ? $model->count : 0;

            $items[] = [
                '1',
                $model->ID,
                $model->count
            ];
            echo "    > item ".++$all." from ".$itemsCount." items \r \n";
            ++$i;
            if($i == 1000){
                $this->batchInsert('shopsGoods', [
                    'shopID',
                    'itemID',
                    'count'],
                    $items
                );
                $i = 0;
                $items = [];
            }
        }

        if($items){
            $this->batchInsert('shopsGoods', [
                'shopID',
                'itemID',
                'count'],
                $items
            );
        }
    }

    public function down()
    {
        echo "m160517_133403_update_order_source cannot be reverted.\n";

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
