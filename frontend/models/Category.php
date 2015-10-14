<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.10.15
 * Time: 17:25
 */

namespace frontend\models;


class Category extends \common\models\Category{

    public function goods(){
        $categories = [];

        $tCats = self::find()->where(['like', 'Code', $this->Code.'%', false])->andWhere(['menu_show' => 1]);

        foreach($tCats->each() as $category){
            $categories[] = $category->ID;
        }

        $goods = Good::find()->where([
            'in', 'GroupID', $categories
        ])->andWhere('show_img = 1 AND ico != \'\' AND deleted = 0 AND (PriceOut1 != 0 AND PriceOut2 != 0)');

        return $goods;
    }

}