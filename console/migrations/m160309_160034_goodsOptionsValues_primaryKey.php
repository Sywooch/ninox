<?php

use yii\db\Migration;

class m160309_160034_goodsOptionsValues_primaryKey extends Migration
{
    public function up()
    {
        $this->addPrimaryKey('good', \common\models\GoodOptionsValue::tableName(), ['good', 'option']);
    }

    public function down()
    {
        $this->dropPrimaryKey('good', \common\models\GoodOptionsValue::tableName());

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
