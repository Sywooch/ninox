<?php

use yii\db\Migration;
use yii\db\Schema;

class m160127_124446_subdomains_access_control extends Migration
{
    public function up()
    {
	    $this->createTable('subDomains', [
		    'id'                =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
		    'name'              =>  Schema::TYPE_STRING.' NOT NULL',
		    'autologin'         =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
		    'autologinParams'   =>  Schema::TYPE_TEXT.' NOT NULL',
		    'cashboxId'         =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0'
	    ]);

	    $this->createTable('subDomainsAccess', [
			'userId'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
		    'subDomainId'   =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL'
	    ]);

	    $this->addPrimaryKey('user', 'subDomainsAccess', ['userId', 'subDomainId']);

    }

    public function down()
    {
        $this->dropTable('subDomains');
        $this->dropTable('subDomainsAccess');
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
