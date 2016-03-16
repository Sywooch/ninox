<?php

use yii\db\Migration;

class m160316_154734_banners extends Migration
{
    public function up()
    {
        foreach(['2x2', '1x1.1', '1x1.2', '1x1.3', '1x1.4', '1x2'] as $item){
            $model = new \common\models\BannerType([
                'alias' =>  $item
            ]);

            $model->save(false);
        }
    }

    public function down()
    {
        echo "m160316_154734_banners cannot be reverted.\n";

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
