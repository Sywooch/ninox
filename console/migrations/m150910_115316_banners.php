<?php

use yii\db\Migration;

class m150910_115316_banners extends Migration
{
    public function up()
    {
	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'banners'
				AND `column_name` = 'banner_uk'
				) > 0,
				\"SELECT 0\",
				\"ALTER TABLE `banners` ADD COLUMN `banner_uk` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Баннер для украинской версии';\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");
	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'banners'
				AND `column_name` = 'link_uk'
				) > 0,
				\"SELECT 0\",
				\"ALTER TABLE `banners` ADD COLUMN `link_uk` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Ссылка для украинской версии';\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");
	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'banners'
				AND `column_name` = 'banner_be'
				) > 0,
				\"SELECT 0\",
				\"ALTER TABLE `banners` ADD COLUMN `banner_be` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Баннер для беларуской версии';\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");
	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'banners'
				AND `column_name` = 'link_be'
				) > 0,
				\"SELECT 0\",
				\"ALTER TABLE `banners` ADD COLUMN `link_be` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Ссылка для беларусской версии';\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");
	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'banners'
				AND `column_name` = 'timeFrom'
				) > 0,
				\"ALTER TABLE `banners` DROP COLUMN `timeFrom`;\",
				\"SELECT 0\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");
	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'banners'
				AND `column_name` = 'timeTo'
				) > 0,
				\"ALTER TABLE `banners` DROP COLUMN `timeTo`;\",
				\"SELECT 0\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");

	    /*$this->execute("ALTER TABLE `banners`
            CHANGE `banner` `banner_ru` VARCHAR(255) COMMENT 'Баннер для русской версии',
            CHANGE `link` `link_ru` VARCHAR(255) COMMENT 'Ссылка для русской версии',
            CHANGE `dateFrom` `dateStart` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            CHANGE `dateTo` `dateEnd` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");*/

	    $this->execute("UPDATE `banners` SET `banner_uk` = `banner_ru`, `link_uk` = `link_ru`");
    }

    public function down()
    {
        echo "m150910_115316_banners cannot be reverted.\n";
        return false;
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
