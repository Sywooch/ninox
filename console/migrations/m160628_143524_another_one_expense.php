<?php

use yii\db\Migration;

class m160628_143524_another_one_expense extends Migration
{
    public function up()
    {
        $expence = new \common\models\CostsType([
            'type'  =>  'cashboxExpenses'
        ]);

        $expence->save(false);
    }

    public function down()
    {
        \common\models\CostsType::deleteAll(['type' => 'cashboxExpenses']);
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
