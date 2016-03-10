<?php

use yii\db\Migration;

class m160229_144843_good_edit extends Migration
{
    public function up()
    {
        $this->renameColumn('goods', 'gabarity','dimensions');
        $this->renameColumn('goods', 'shyryna','width');
        $this->renameColumn('goods', 'vysota','height');
        $this->renameColumn('goods', 'dovgyna','length');
        $this->renameColumn('goods', 'dyametr','diameter');
        $this->renameColumn('goods', 'Measure1','measure');

        $this->addColumn(\common\models\GoodsPhoto::tableName(), 'order', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');

        $goods = \common\models\Good::find()->where('`ico` != \'\'');
        $goodsCount = $goods->count();


        echo "    > updating goods photos: find ".$goodsCount.' photos...';

        $i = 0;

        foreach($goods->each() as $good){
            $photo = new \common\models\GoodsPhoto([
                'itemid'    =>  $good->ID,
                'ico'       =>  $good->ico,
                'order'     =>  '1',
            ]);

            $i++;

            echo "\r\n    > saving photo {$i} of {$goodsCount}...";

            if($photo->save(false)){
                echo ' saved!';
            }else{
                echo ' problems...';
            }
        }

        $this->dropColumn('goods', 'ico');
    }

    public function down()
    {
        $this->renameColumn('goods', 'dimensions','gabarity');
        $this->renameColumn('goods', 'width','shyryna');
        $this->renameColumn('goods', 'height','vysota');
        $this->renameColumn('goods', 'length','dovgyna');
        $this->renameColumn('goods', 'diameter','dyametr');
        $this->renameColumn('goods', 'measure','Measure1');

        $this->addColumn('goods', 'ico', \yii\db\Schema::TYPE_STRING);

        $this->dropColumn(\common\models\GoodsPhoto::tableName(), 'order');

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
