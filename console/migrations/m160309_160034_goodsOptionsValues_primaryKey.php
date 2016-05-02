<?php

use yii\db\Migration;

class m160309_160034_goodsOptionsValues_primaryKey extends Migration
{
    public function up()
    {
        $this->execute("DELETE s1 FROM `goodsoptions_values` s1, `goodsoptions_values` s2 WHERE s1.value > s2.value AND s1.good = s2.good AND s1.option = s2.option");
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
