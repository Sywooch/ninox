<?php

use yii\db\Migration;

class m160203_142533_subdomains_store extends Migration
{
    public function up()
    {
        $this->addColumn('subDomains', 'storeId', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
        $this->addColumn('shops', 'default', \yii\db\Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');

        foreach(\common\models\SubDomain::find()->each() as $domain){
            $domain->storeId = \common\models\Cashbox::find()->select("store")->where(['id' => $domain->cashboxId])->scalar();
            $domain->save(false);
        }

        $shop = \common\models\Shop::findOne(['id' => 1]);
        $shop->default = 1;
        $shop->save(false);
    }

    public function down()
    {
        $this->dropColumn('subDomains', 'storeId');
        $this->dropColumn('shops', 'default');

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
