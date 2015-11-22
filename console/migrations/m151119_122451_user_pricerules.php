<?php

use yii\db\Migration;
use yii\db\Schema;

class m151119_122451_user_pricerules extends Migration
{
    public function up()
    {
	    $this->createTable('users_pricerules', [
				'ID'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT',
				'UserGUID'  =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL',
				'Formula'   =>  Schema::TYPE_TEXT.' CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL',
				'Enabled'   =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
				'Priority'  =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
				'PRIMARY KEY (`ID`)',
				'INDEX `UserGUID` (`UserGUID`) USING BTREE',
            ]);
    }

	public function down()
	{
		$this->dropTable('users_pricerules');
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
