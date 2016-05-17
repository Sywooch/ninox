<?php

use yii\db\Migration;

class m160512_112832_join_orders_fix extends Migration
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
        echo "m160512_112832_join_orders_fix cannot be reverted.\n";

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
