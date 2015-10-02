<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

class m150905_082946_lang extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}

		$this->createTable('{{%lang}}', [
			'id' => Schema::TYPE_PK,
			'url' => Schema::TYPE_STRING . '(255) NOT NULL',
			'local' => Schema::TYPE_STRING . '(255) NOT NULL',
			'name' => Schema::TYPE_STRING . '(255) NOT NULL',
			'shortName' => Schema::TYPE_STRING . '(255) NOT NULL',
		], $tableOptions);

		$this->batchInsert('lang', ['url', 'local', 'name', 'shortName'], [
			['ru', 'ru-RU', 'Русский', 'Рус'],
			['uk', 'uk-UA', 'Українська', 'Укр'],
		]);
	}

	public function safeDown()
	{
		$this->dropTable('{{%lang}}');
	}
}
