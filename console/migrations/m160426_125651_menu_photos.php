<?php

use yii\db\Migration;
use yii\db\Schema;

class m160426_125651_menu_photos extends Migration
{
    public function up()
    {
        $this->createTable('categoryPhotos', [
            'categoryID'    =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'order'         =>  Schema::TYPE_INTEGER.' NOT NULL',
            'photo'         =>  Schema::TYPE_STRING,
        ]);

        $this->addPrimaryKey('photo', 'categoryPhotos', ['categoryID', 'order']);

        $categoryPhotos = [
            '78'    =>  ['rezinki.png', 'kolie.png'], // Бижутерия
            '202'   =>  ['mozaika.png', 'tychynky.png', 'shnur.png'], // Рукоделие
            '156'   =>  ['tyolka.png', 'chasy.png', 'venok.png'], // Аксессуары
            '331'   =>  ['kolyaska.png', 'kreslo.png', 'velik.png'], // Товары для детей
            '367'   =>  ['rascheska.png', 'pudra.png', 'manikurniy-nabor.png'], // Красота и здоровье
            '527'   =>  ['fonarik.png', 'korzina.png', 'gel.png'], // Всё для дома
            '605'   =>  ['shariki.png', 'podushka.png', 'svecha.png'], // Праздники
            '500'   =>  ['fen.png', 'epilyator.png', 'mashinka.png'], // Техника для красоты
            '640'   =>  ['derzhatel.png', 'naushniki.png', 'chehol.png'], // Аксессуары для телефонов
        ];

        echo "    > Adding some photos... \r\n";

        foreach($categoryPhotos as $categoryID => $photos){
            foreach($photos as $order => $photo){
                $categoryPhoto = new \common\models\CategoryPhoto([
                    'categoryID'    =>  $categoryID,
                    'photo'         =>  $photo,
                    'order'         =>  ($order + 1)
                ]);

                $categoryPhoto->save(false);
            }
        }
    }

    public function down()
    {
        return $this->dropTable('categoryPhotos');
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
