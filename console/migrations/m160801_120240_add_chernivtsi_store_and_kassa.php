<?php

use common\models\Shop;
use yii\db\Migration;

class m160801_120240_add_chernivtsi_store_and_kassa extends Migration
{
    public function up()
    {

        /**
         * Создаём склад в Черновцах
         */
        echo '    > Создаём склад в Черновцах... ';
        $store = new Shop([
            'name'  =>  'Склад Черновцы',
            'type'  =>  Shop::TYPE_STORE
        ]);

        if($store->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Создаём розничного покупателя для Черновцов
         */
        echo '    > Создаём розничного покупателя для Черновцов... ';
        $customer = new \common\models\Customer([
            'name'      =>  'Розничный',
            'surname'   =>  'покупатель',
        ]);

        if($customer->save(false)){
            echo "Успех \r\n";
        }


        /**
         * Создаём оптового покупателя для Черновцов
         */
        echo '    > Создаём оптового покупателя для Черновцов... ';
        $wholesaleCustomer = new \common\models\Customer([
            'name'      =>  'Оптовый',
            'surname'   =>  'покупатель',
        ]);

        if($wholesaleCustomer->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Создаём кассу для Черновцов
         */
        echo '    > Создаём кассу для Черновцов... ';
        $cashbox = new \common\models\Cashbox([
            'name'                      =>  'Касса Черновцы',
            'defaultCustomer'           =>  $customer->ID,
            'defaultWholesaleCustomer'  =>  $wholesaleCustomer->ID,
            'store'                     =>  $store->id,
        ]);

        if($cashbox->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Домен для Черновцов
         */
        echo '    > Домен для Черновцов... ';
        $domain = new \common\models\SubDomain([
            'name'              =>  'cv',
            'autologin'         =>  true,
            'cashboxId'         =>  $cashbox->ID,
            'storeId'           =>  $store->id
        ]);

        if($domain->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Пользователи для Черновцов
         */
        echo "    > Пользователи для Черновцов...\r\n";
        echo '    > Пользователь Наташа... ';
        $userNatasha = new \common\models\Siteuser([
            'username'  =>  'cv.natasha',
            'name'      =>  'Наташа',
            'active'    =>  1,
            'domain'    =>  $domain->id
        ]);

        if($userNatasha->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Раз созданы пользователи - можем сделать под них автологин
         */
        echo '    > Раз созданы пользователи - можем сделать под них автологин... ';
        $domain->autologinParams = [
            [
                'user'  =>  [$userNatasha->id],
                'ip'    =>  '80.78.45.114'
            ],
            [
                'user'  =>  '30',
                'ip'    =>  '80.78.45.114'
            ]
        ];

        if($domain->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Даем доступ пользователю root к кассе на Черновцах
         */
        echo '    > Даем доступ пользователю root к кассе на Черновцах... ';
        $access = new \common\models\SubDomainAccess([
            'userId'        =>  30,
            'subDomainId'   =>  $domain->id
        ]);

        if($access->save(false)){
            echo "Успех \r\n";
        }
    }

    public function down()
    {
        echo "m160801_120240_add_chernivtsi_store_and_kassa cannot be reverted.\n";

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
