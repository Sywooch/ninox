<?php

use yii\db\Migration;

class m160131_110736_default_cashboxes extends Migration
{
    public function up()
    {

        $this->dropColumn('shops', 'id');
        $this->addColumn('shops', 'id', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT');


        /**
         * Создаём стандартный склад
         */
        echo '     > Создаём стандартный склад... ';
        $defaultStore = new \common\models\Shop([
            'name'  =>  'Склад троещина',
            'type'  =>  1
        ]);

        if($defaultStore->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Создаём склад в Хмельницком
         */
        echo '     > Создаём склад в Хмельницком... ';
        $store = new \common\models\Shop([
            'name'  =>  'Склад Хмельницкий',
            'type'  =>  1
        ]);

        if($store->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Создаём дефолтного розничного покупателя
         */
        echo '     > Создаём дефолтного розничного покупателя... ';
        $defaultCustomer = new \common\models\Customer([
            'name'      =>  'Розничный',
            'surname'   =>  'покупатель',
        ]);

        if($defaultCustomer->save(false)){
            echo "Успех \r\n";
        }


        /**
         * Создаём дефолтного оптового покупателя
         */
        echo '     > Создаём дефолтного оптового покупателя... ';
        $defaultWholesaleCustomer = new \common\models\Customer([
            'name'      =>  'Оптовый',
            'surname'   =>  'покупатель',
        ]);

        if($defaultWholesaleCustomer->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Создаём розничного покупателя для Хмельницкого
         */
        echo '     > Создаём розничного покупателя для Хмельницкого... ';
        $customer = new \common\models\Customer([
            'name'      =>  'Розничный',
            'surname'   =>  'покупатель',
        ]);

        if($customer->save(false)){
            echo "Успех \r\n";
        }


        /**
         * Создаём оптового покупателя для Хмельницкого
         */
        echo '     > Создаём оптового покупателя для Хмельницкого... ';
        $wholesaleCustomer = new \common\models\Customer([
            'name'      =>  'Оптовый',
            'surname'   =>  'покупатель',
        ]);

        if($wholesaleCustomer->save(false)){
            echo "Успех \r\n";
        }


        /**
         * Создаём дефолтную кассу
         */
        echo '     > Создаём дефолтную кассу... ';
        $defaultCashbox = new \common\models\Cashbox([
            'name'                      =>  'Касса 1',
            'defaultCustomer'           =>  $defaultCustomer->ID,
            'defaultWholesaleCustomer'  =>  $defaultWholesaleCustomer->ID,
            'store'                     =>  $defaultStore->id,
            'default'                   =>  1
        ]);

        if($defaultCashbox->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Создаём кассу для Хмельницкого
         */
        echo '     > Создаём кассу для Хмельницкого... ';
        $cashbox = new \common\models\Cashbox([
            'name'                      =>  'Касса Хмельницкий',
            'defaultCustomer'           =>  $customer->ID,
            'defaultWholesaleCustomer'  =>  $wholesaleCustomer->ID,
            'store'                     =>  $store->id,
        ]);

        if($cashbox->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Создаём стандартные домены
         */
        echo '     > Создаём стандартные домены... ';
        $defaultDomain = new \common\models\SubDomain([
            'name'              =>  '',
            'autologin'         =>  false,
            'cashboxId'         =>  $defaultCashbox->ID
        ]);

        if($defaultDomain->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Домен для Хмельницкого
         */
        echo '     > Домен для Хмельницкого... ';
        $domain = new \common\models\SubDomain([
            'name'              =>  'kh',
            'autologin'         =>  true,
            'cashboxId'         =>  $cashbox->ID
        ]);

        if($domain->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Пользователи для Хмельницкого
         */
        echo '     > Пользователи для Хмельницкого... '."\r\n";
        echo '     > Пользователь Ирина... ';
        $userIrina = new \common\models\Siteuser([
            'username'  =>  'kh.irina',
            'name'      =>  'Ирина',
            'active'    =>  1,
            'domain'    =>  $domain->id
        ]);

        if($userIrina->save(false)){
            echo "Успех \r\n";
        }

        echo '     > Пользователь Марина... ';
        $userMarina = new \common\models\Siteuser([
            'username'  =>  'kh.marina',
            'name'      =>  'Марина',
            'active'    =>  1,
            'domain'    =>  $domain->id
        ]);

        if($userMarina->save(false)){
            echo "Успех \r\n";
        }

        /**
         * Раз созданы пользователи - можем сделать под них автологин
         */
        echo '     > Раз созданы пользователи - можем сделать под них автологин... ';
        $domain->autologinParams = [
            [
                'user'  =>  [$userIrina->id, $userMarina->id],
                'ip'    =>  '95.111.143.239'
            ],
            [
                'user'  =>  '30',
                'ip'    =>  '80.78.45.114'
            ]
        ];

        if($domain->save(false)){
            echo "Успех \r\n";
        }
    }

    public function down()
    {
        echo "m160131_110736_default_cashboxes cannot be reverted.\n";

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
