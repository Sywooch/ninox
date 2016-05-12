<?php

use yii\db\Migration;
use yii\db\Schema;

class m160329_160611_improve_partners_table extends Migration
{
    public function up()
    {
        $this->addColumn('partners', 'deliveryParam', Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
        $this->addColumn('partners', 'deliveryInfo', Schema::TYPE_STRING);
        $this->addColumn('partners', 'paymentParam', Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
        $this->addColumn('partners', 'paymentInfo', Schema::TYPE_STRING);

        $this->renameColumn('partners', 'PaymentType', 'paymentType');
        $this->renameColumn('partners', 'shippingType', 'deliveryType');

        $this->execute("UPDATE `partners` SET `deliveryType` = SPLIT_STR_TO_STR(`deliveryType`, ',', 1)");
        $this->execute("UPDATE `partners` SET `deliveryType` = '1' WHERE `deliveryType` = 'Адресная доставка'");
        $this->execute("UPDATE `partners` SET `deliveryType` = '2' WHERE `deliveryType` = 'Новая почта'");
        $this->execute("UPDATE `partners` SET `deliveryType` = '3' WHERE `deliveryType` = 'Самовывоз'");
        $this->execute("UPDATE `partners` SET `deliveryType` = '0' WHERE `deliveryType` = '' OR `deliveryType` IS NULL");

        $this->execute("UPDATE `partners` SET `paymentType` = SPLIT_STR_TO_STR(`paymentType`, ',', 1)");
        $this->execute("UPDATE `partners` SET `paymentType` = '1' WHERE `paymentType` = 'наложенным платежом' OR `paymentType` = 'Наложенный платёж'");
        $this->execute("UPDATE `partners` SET `paymentType` = '2' WHERE `paymentType` = 'на банковскую карту' OR `paymentType` = 'русский стандарт'");
        $this->execute("UPDATE `partners` SET `paymentType` = '0' WHERE `paymentType` = 'способ платежа не выбран' OR `paymentType` = '' OR `paymentType` IS NULL OR `paymentType` = 'РЅР°Р»РѕР¶РµРЅРЅС‹Рј РїР»Р°С‚РµР¶РѕРј'");

        $this->alterColumn('partners', 'paymentType', Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
        $this->alterColumn('partners', 'deliveryType', Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
    }


    public function down()
    {
        echo "m160329_160611_improve_partners_table cannot be reverted.\n";

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
