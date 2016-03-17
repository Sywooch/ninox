<?php

use yii\db\Migration;

class m160316_125657_rename_category_to_categoryCode extends Migration
{
    public function up()
    {
        $this->renameColumn(\cashbox\models\CashboxItem::tableName(), 'category', 'categoryCode');
        $this->renameColumn(\common\models\SborkaItem::tableName(), 'category', 'categoryCode');
    }

    public function down()
    {
        $this->renameColumn(\cashbox\models\CashboxItem::tableName(), 'categoryCode', 'category');
        $this->renameColumn(\common\models\SborkaItem::tableName(), 'categoryCode', 'category');

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
