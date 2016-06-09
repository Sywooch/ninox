<?php

use yii\db\Migration;

class m160609_135959_some_changes_for_charts extends Migration
{
    public function up()
    {
        $this->addColumn('goods', 'disableConfirmed', $this->smallInteger(1)->unsigned()->defaultValue(0));
        $this->addColumn('shops', 'daySalesPlan', $this->integer()->unsigned()->defaultValue(0));

        $this->createIndex('disableConfirmed', 'goods', 'disableConfirmed');

        \common\models\Shop::updateAll(['daySalesPlan' => 25000]);
    }

    public function down()
    {
        $this->dropColumn('goods', 'disableConfirmed');
        $this->dropColumn('shops', 'daySalesPlan');

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
