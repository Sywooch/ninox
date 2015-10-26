<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.10.15
 * Time: 17:25
 */

namespace frontend\models;


use yii\db\Command;
use yii\db\Query;

class Category extends \common\models\Category{
    public function goods(){
	    $goods = Good::find()->
		    select('`goods`.*, `goodsgroups`.`Code` AS `category`')->
		    leftJoin('goodsgroups', '`goods`.`GroupID` = `goodsgroups`.`ID`', [])->
		    where(['like', '`goodsgroups`.`Code`', $this->Code.'%', false])->
		    andWhere(['`goodsgroups`.`menu_show`' => 1])->
		    andWhere('`goods`.`show_img` = 1 AND `goods`.`ico` != \'\' AND `goods`.`deleted` = 0 AND (`goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0)')->
		    orderBy('IF (`goods`.`count` <= \'0\' AND `goods`.`isUnlimited` = \'0\', \'FIELD(`goods`.`count` DESC)\', \'FIELD()\')');
        return $goods;
    }
}