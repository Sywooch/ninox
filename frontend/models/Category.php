<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.10.15
 * Time: 17:25
 */

namespace frontend\models;

use common\models\GoodOptionsValue;

class Category extends \common\models\Category{

    public function goods(){
	    return Good::find()
		    ->leftJoin('goodsgroups', '`goods`.`GroupID` = `goodsgroups`.`ID`')
		    ->where(['like', '`goodsgroups`.`Code`', $this->Code.'%', false])
		    ->andWhere(['`goodsgroups`.`enabled`' => 1])
		    ->andWhere('`goods`.`show_img` = 1 AND `goods`.`deleted` = 0 AND (`goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0)')
		    ->orderBy('IF (`goods`.`count` <= \'0\' AND `goods`.`isUnlimited` = \'0\', \'FIELD(`goods`.`count` DESC)\', \'FIELD()\')');
    }

	public static function getMenu(){
		$cats = self::find()
			->select(['ID', 'Name', 'Code', 'link', 'listorder', 'enabled', 'imgSrc'])
			->orderBy('Code')
			->all();
		return self::buildTree($cats);
	}

	public function getFilters(){
		$filters = [];
		foreach(GoodOptionsValue::find()
			->leftJoin('goods', '`goods`.`ID` = `goodsoptions_values`.`good` AND `goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0 AND `goods`.`Deleted` = 0 AND `goods`.`show_img` = 1')
			->leftJoin('goodsgroups', '`goods`.`GroupID` = `goodsgroups`.`ID`')
			->where(['like', '`goodsgroups`.`Code`', $this->Code.'%', false])
			->joinWith('goodOptions')
			->joinWith('goodOptionsVariants')
			->all() as $goodOptVal){
			//var_dump($goodOptVal->goodOptions[0]->name);die();
			if(!empty($goodOptVal->goodOptions)){
				$filters[$goodOptVal->goodOptions[0]->id]['name'] = $goodOptVal->goodOptions[0]->name;
				if(!empty($goodOptVal->goodOptionsVariants)){
					$filters[$goodOptVal->goodOptions[0]->id]['options'][$goodOptVal->goodOptionsVariants[0]->id]['label'] = $goodOptVal->goodOptionsVariants[0]->value;
					isset($filters[$goodOptVal->goodOptions[0]->id]['options'][$goodOptVal->goodOptionsVariants[0]->id]['count']) ?
						$filters[$goodOptVal->goodOptions[0]->id]['options'][$goodOptVal->goodOptionsVariants[0]->id]['count']++ :
						$filters[$goodOptVal->goodOptions[0]->id]['options'][$goodOptVal->goodOptionsVariants[0]->id]['count'] = 1;
				}
			}
			//var_dump($goodOptVal->goodOptionsVariants);echo '<br><br>';//die();
		}
		return $filters;
	}

}