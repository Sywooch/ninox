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

	private $_filters;

    public function goods(){
	    $values = $names = [];

	    foreach($this->filters as $filterArray){
		    if(!empty($filterArray['checked'])){
			    $names[] = $filterArray['name'];
			    $values = array_merge($values, $filterArray['checked']);
		    }
	    }

	    $return = Good::find()
		    ->leftJoin('goodsgroups', '`goods`.`GroupID` = `goodsgroups`.`ID`')
		    ->where(['like', '`goodsgroups`.`Code`', $this->Code.'%', false]);

	    if(!empty($values) && !empty($names)){
		    $return
			    ->joinWith('filters')
			    ->andWhere(['IN', '`goodsoptions`.`name`', $names])
			    ->andWhere(['IN', '`goodsoptions_values`.`value`', $values])
			    ->groupBy('goodsoptions_values.good')
			    ->having('COUNT(goodsoptions_values.good) >= '.sizeof($names));
	    }

	    $return->andWhere(['`goodsgroups`.`enabled`' => 1])
		    ->andWhere('`goods`.`show_img` = 1 AND `goods`.`deleted` = 0 AND (`goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0)')
		    ->orderBy('IF (`goods`.`count` <= \'0\' AND `goods`.`isUnlimited` = \'0\', \'FIELD(`goods`.`count` DESC)\', \'FIELD()\')');

	    return $return;
    }

	public static function getMenu(){
		$cats = self::find()
			->select(['ID', 'Name', 'Code', 'link', 'listorder', 'enabled', 'imgSrc'])
			->orderBy('Code')
			->all();
		return self::buildTree($cats);
	}

	public function getFilters(){
		if(!empty($this->_filters)){
			return $this->_filters;
		}

		$filters = [];
		$varCount = [];
		$itemVars = [];
		$varChecked = [];

		foreach(GoodOptionsValue::find()
			->leftJoin('goods', '`goods`.`ID` = `goodsoptions_values`.`good` AND `goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0 AND `goods`.`Deleted` = 0 AND `goods`.`show_img` = 1')
			->leftJoin('goodsgroups', '`goods`.`GroupID` = `goodsgroups`.`ID`')
			->where(['like', '`goodsgroups`.`Code`', $this->Code.'%', false])
			->joinWith(['goodOptions', 'goodOptionsVariants'])
			->all() as $filterOption){
			if(!empty($filterOption->goodOptions)){
				$go = $filterOption->goodOptions;
				isset($filters[$go->id]['name']) ? '' :
					$filters[$go->id]['name'] = $go->name;
				isset($filters[$go->id]['id']) ? '' :
					$filters[$go->id]['id'] = $go->id;
				isset($filters[$go->id]['checked']) ? '' :
					$filters[$go->id]['checked'] = array_diff(explode(',', \Yii::$app->request->get(preg_replace('/\s/', '_', $go->name))), ['']);
				if(!empty($filterOption->goodOptionsVariants)){
					$gv = $filterOption->goodOptionsVariants;
					isset($filters[$go->id]['options'][$gv->id]['label']) ? '' :
						$filters[$go->id]['options'][$gv->id]['label'] = $gv->value;

					$itemVars[$filterOption->good][$gv->id] = in_array($gv->id, $filters[$go->id]['checked']);

					empty($filters[$go->id]['checked']) ? '' : $varChecked[$go->id][$gv->id] = in_array($gv->id, $filters[$go->id]['checked']);

					//$filters[$go->id]['options'][$gv->id]['count'] += 1;//empty($filters[$go->id]['checked']) ? 0 : 1;
				}
			}
		}

		//var_dump($varChecked);

		$filterNames = array_column($filters, 'name', 'id');

/*		foreach(\Yii::$app->request->get() as $name => $value){
			$name = preg_replace('/\_/', ' ', $name);
			$id = array_search($name, $filterNames);
			echo '<pre>';
			var_dump($id);
			echo '</pre>';
		}*/

		foreach($itemVars as $k => $itemVar){
			$step = 1;
			$break = false;
			$checked = array_intersect($itemVar, [true]);
/*			echo '<pre>'.$k;
			var_dump(sizeof($checked));
			var_dump(array_intersect_key($itemVar, $varChecked));
			echo '</pre>';*/
			if(sizeof($varChecked) == sizeof($checked)){
				foreach($itemVar as $key => $value){
					isset($varCount[$key]) ? $varCount[$key]++ : $varCount[$key] = 1;
				}
			}else{
				foreach(\Yii::$app->request->get() as $name => $value){
					$name = preg_replace('/\_/', ' ', $name);
					$id = array_search($name, $filterNames);
					if($id){
						$break = false;
						foreach(array_intersect_key($itemVar, $varChecked[$id]) as $k => $v){
							echo '<pre>';
							var_dump($step);
							echo '</pre>';
							if(($step == 1 && !$v)){
								isset($varCount[$k]) ? $varCount[$k]++ : $varCount[$k] = 1;
								$break = true;
							}elseif(($step < sizeof($varChecked) && $v)){
								isset($varCount[$k]) ? $varCount[$k]++ : $varCount[$k] = 1;
							}
							$step++;
						}
						if($break){
							break;
						}
					}
				}
			}
		}
			/*foreach($varChecked as $vars){
				$intersect = array_intersect($itemVar, array_keys($vars));

				if(empty($intersect)){
					break;
				}else{
					$step++;
					foreach($intersect as $key){
						if($vars[$key] && $step == sizeof($varChecked)){

								echo '<pre>'.$k.'First';
								var_dump($itemVar);
								var_dump($vars);
								echo '</pre>';

							foreach($itemVar as $var){
								isset($varCount[$var]) ? $varCount[$var]++ : $varCount[$var] = 1;
							}
						}elseif($vars[$key]){
								echo '<pre>'.$k.'Second';
								var_dump($intersect);
								echo '</pre>';

							isset($varCount[$key]) ? '' : $varCount[$key] = 1;
							$break = true;
							break;
						}else{
							echo '<pre>'.$k.'Third';
							var_dump($intersect);
							echo '</pre>';

							isset($varCount[$key]) ? '' : $varCount[$key] = 1;
							$break = true;
							break;
						}
					}
					if($break){
						break;
					}
				}
			}
		}*/

/*		echo '<pre>';
		var_dump($varCount);
		echo '</pre>';*/

		foreach($filters as $go => $filter){
			foreach($filter['options'] as $gv => $val){
				$filters[$go]['options'][$gv]['count'] = isset($varCount[$gv]) ? $varCount[$gv] : 0;
			}
		}

		//var_dump($varCount);

		return $this->_filters = $filters;
	}

}