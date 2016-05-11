<?php

use yii\db\Schema;
use yii\db\Migration;

class m150528_143719_new_partners_tables extends Migration
{
    public function up()
    {
        $this->createTable('partnersAddresses', [
            'ID'            =>  Schema::TYPE_INTEGER.' NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'partnerID'     =>  Schema::TYPE_INTEGER.' NOT NULL',
            'country'       =>  Schema::TYPE_TEXT,
            'region'        =>  Schema::TYPE_TEXT,
            'city'          =>  Schema::TYPE_TEXT,
            'address'       =>  Schema::TYPE_TEXT,
            'shippingType'  =>  Schema::TYPE_INTEGER.' NULL DEFAULT 0',
            'shippingParam' =>  Schema::TYPE_STRING,
            'paymentType'   =>  Schema::TYPE_INTEGER.' NULL DEFAULT 0',
            'paymentParam'  =>  Schema::TYPE_STRING,
            'primary'       =>  Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0'
        ]);

        $this->createTable('partnersContacts', [
            'ID'        =>  Schema::TYPE_INTEGER.' NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'partnerID' =>  Schema::TYPE_INTEGER.' NOT NULL',
            'type'      =>  Schema::TYPE_INTEGER.' NOT NULL',
            'value'     =>  Schema::TYPE_STRING.' NOT NULL',
            'primary'       =>  Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0'
        ]);

        $this->execute("DROP FUNCTION IF EXISTS SPLIT_STR_TO_STR;
			CREATE FUNCTION SPLIT_STR_TO_STR(
			  x VARCHAR(255),
			  delim VARCHAR(12),
			  pos INT
			)
			RETURNS VARCHAR(255)
			BEGIN
			DECLARE s VARCHAR(255);
			SELECT TRIM(REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
			       CHAR_LENGTH(SUBSTRING_INDEX(x, delim, pos - 1)) + 1),
       delim, '')) INTO s;
            IF(s = '') THEN
                RETURN NULL;
            ELSE
                RETURN s;
            END IF;
            END;

            DROP FUNCTION IF EXISTS SPLIT_STR_TO_INT;
			CREATE FUNCTION SPLIT_STR_TO_INT(
			  x VARCHAR(255),
			  delim VARCHAR(12),
			  pos INT
			)
			RETURNS VARCHAR(255)
			BEGIN
			DECLARE s VARCHAR(255);
			SELECT TRIM(REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
			       CHAR_LENGTH(SUBSTRING_INDEX(x, delim, pos - 1)) + 1),
       delim, '')) INTO s;
            IF(LENGTH(s) > 1 OR s = '') THEN
                RETURN NULL;
            ELSE
                RETURN s;
            END IF;
            END;");

        $this->execute("INSERT INTO `partnersAddresses` SELECT NULL AS `ID`,
`ID` AS `partnerID`,
'' AS `country`,
SPLIT_STR_TO_STR(`City`, ',', 2) AS `city`,
SPLIT_STR_TO_STR(`City`, ',', 1) AS `region`,
`Address` AS `address`,
SPLIT_STR_TO_INT(`ShippingType`, ',', 1) AS `shippingType`,
SPLIT_STR_TO_INT(`ShippingType`, ',', 2) AS `shippingParam`,
SPLIT_STR_TO_INT(`PaymentType`, ',', 1) AS `paymentType`,
SPLIT_STR_TO_INT(`PaymentType`, ',', 2) AS `paymentParam`,
'0' AS `primary`
FROM `partners`");


        $this->execute("INSERT INTO `partnersContacts` SELECT NULL AS `ID`, `partners`.`ID` AS `partnerID`, '1' AS `type`, `email` AS `value`, '1' AS `primary` FROM `partners` WHERE `email` != ''");
        $this->execute("INSERT INTO `partnersContacts` SELECT NULL AS `ID`, `partners`.`ID` AS `partnerID`, '2' AS `type`, `Phone` AS `value`, '1' AS `primary` FROM `partners` WHERE `Phone` != ''");
    }

    public function down()
    {
        $this->dropTable('partnersAddresses');
        $this->dropTable('partnersContacts');
        $this->execute("DROP FUNCTION IF EXISTS SPLIT_STR");

        echo 'reverted!';
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
