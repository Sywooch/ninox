<?php

use yii\db\Migration;

class m160525_150436_menu_img extends Migration
{
    public function up()
    {
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 156, 'order' => 1]);
        if($catPhoto){
            $catPhoto->photo = 'chasy.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 156, 'order' => 2]);
        if($catPhoto){
            $catPhoto->photo = 'tyolka.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 202, 'order' => 1]);
        if($catPhoto){
            $catPhoto->photo = 'shnur.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 202, 'order' => 2]);
        if($catPhoto){
            $catPhoto->photo = 'mozaika.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 367, 'order' => 1]);
        if($catPhoto){
            $catPhoto->photo = 'manikurniy-nabor.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 367, 'order' => 3]);
        if($catPhoto){
            $catPhoto->photo = 'rascheska.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 605, 'order' => 1]);
        if($catPhoto){
            $catPhoto->photo = 'podushka.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 605, 'order' => 2]);
        if($catPhoto){
            $catPhoto->photo = 'shariki.png';
            $catPhoto->save(false);
        }

    }

    public function down()
    {
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 156, 'order' => 2]);
        if($catPhoto){
            $catPhoto->photo = 'chasy.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 156, 'order' => 1]);
        if($catPhoto){
            $catPhoto->photo = 'tyolka.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 202, 'order' => 2]);
        if($catPhoto){
            $catPhoto->photo = 'shnur.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 202, 'order' => 1]);
        if($catPhoto){
            $catPhoto->photo = 'mozaika.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 367, 'order' => 3]);
        if($catPhoto){
            $catPhoto->photo = 'manikurniy-nabor.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 367, 'order' => 1]);
        if($catPhoto){
            $catPhoto->photo = 'rascheska.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 605, 'order' => 2]);
        if($catPhoto){
            $catPhoto->photo = 'podushka.png';
            $catPhoto->save(false);
        }
        $catPhoto = \common\models\CategoryPhoto::findOne(['categoryID' => 605, 'order' => 1]);
        if($catPhoto){
            $catPhoto->photo = 'shariki.png';
            $catPhoto->save(false);
        }

        echo "m160525_150436_menu_img cannot be reverted.\n";

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
