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
	    $goods = Good::find()->
		    where(['like', '`goodsgroups`.`Code`', $this->Code.'%', false])->
		    andWhere(['`goodsgroups`.`menu_show`' => 1])->
		    andWhere('`goods`.`show_img` = 1 AND `goods`.`deleted` = 0 AND (`goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0)')->
		    orderBy('IF (`goods`.`count` <= \'0\' AND `goods`.`isUnlimited` = \'0\', \'FIELD(`goods`.`count` DESC)\', \'FIELD()\')');
        return $goods;
    }

	public static function getMenu(){
		$cats = self::find()->select(['ID', 'Name', 'Code', 'link', 'listorder', 'menu_show', 'imgSrc'])->orderBy('Code')->all();
		return self::buildTree($cats);
	}

}