<?php

use yii\db\Migration;

class m160519_144229_menu_img_sale extends Migration
{
    public function up()
    {
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 78, 'order' => 1]);
        if($catPhoto){
            $catPhoto->photo = 'sale.png';
            $catPhoto->save(false);
        }
    }

    public function down()
    {
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 78, 'order' => 1]);
        if($catPhoto){
            $catPhoto->photo = 'rezinki.png';
            $catPhoto->save(false);
        }

        echo "m160519_144229_menu_img_sale was successfully reverted.\n";

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
