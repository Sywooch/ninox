<?php

use yii\db\Migration;

class m160609_185021_update_users_access extends Migration
{
    public function up()
    {
        $domains = [
            '1' =>  [
                14, 25, 29, 30, 31, 37, 39, 42, 47, 54, 59,
            ],
            '2' =>  [
                30, 34, 35, 37
            ]
        ];

        foreach($domains as $domain => $users){
            foreach($users as $userID){
                $access = new \common\models\SubDomainAccess([
                    'userId'    =>  $userID,
                    'subDomainId'=> $domain
                ]);

                $access->save(false);
            }
        }
    }

    public function down()
    {
        echo "m160609_185021_update_users_access cannot be reverted.\n";

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
