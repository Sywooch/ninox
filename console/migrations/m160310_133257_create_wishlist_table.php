<?php

use yii\db\Migration;
use yii\db\Schema;

class m160310_133257_create_wishlist_table extends Migration
{
    public function up()
    {
        $this->createTable('customersWishlist', [
            'itemID'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'customerID'    =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL',
            'date'          =>  Schema::TYPE_DATETIME.' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
            'price'         =>  Schema::TYPE_DOUBLE.' (24, 2) UNSIGNED NOT NULL DEFAULT 0'
        ], 'ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci');
        $this->addPrimaryKey('itemID', 'customersWishlist', ['itemID', 'customerID']);
    }

    public function down()
    {
        $this->dropTable('customersWishlist');
    }
}
