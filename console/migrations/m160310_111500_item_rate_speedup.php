<?php

use yii\db\Migration;

class m160310_111500_item_rate_speedup extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `items_rate` ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `items_rate` ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci");
        echo "m160310_111500_item_rate_speedup reverted successfully.\n";
        return true;
    }
}
