<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.10.15
 * Time: 17:25
 */

namespace frontend\models;

use common\helpers\Formatter;
use common\models\GoodOptionsValue;

class Category extends \common\models\Category{

	private $_filters;
	private $_minPrice;
	private $_maxPrice;

    public function getItems(){
	    $values = $names = [];

	    foreach($this->filters as $filterArray){
		    if(!empty($filterArray['checked'])){
			    $names[] = $filterArray['name'];
			    $values = array_merge($values, $filterArray['checked']);
		    }
	    }

	    $return = Good::find()
		    ->leftJoin('goodsgroups', '`goods`.`GroupID` = `goodsgroups`.`ID`')
		    ->where(['like', '`goodsgroups`.`Code`', $this->Code.'%', false])
		    ->with('reviews');

	    if(!empty($values) && !empty($names)){
		    $return
			    ->joinWith('filters')
			    ->andWhere(['IN', '`goodsoptions`.`name`', $names])
			    ->andWhere(['IN', '`goodsoptions_values`.`value`', $values])
			    ->groupBy('goodsoptions_values.good')
			    ->having('COUNT(goodsoptions_values.good) >= '.sizeof($names));
	    }

	    if($priceMin = \Yii::$app->request->get('minPrice')){
		    $return->andWhere(['>=', '`goods`.`PriceOut1`', $priceMin]);
	    }

	    if($priceMax = \Yii::$app->request->get('maxPrice')){
		    $return->andWhere(['<=', '`goods`.`PriceOut1`', $priceMax]);
	    }

	    $return->andWhere(['`goodsgroups`.`enabled`' => 1])
		    ->andWhere('`goods`.`show_img` = 1 AND `goods`.`deleted` = 0 AND (`goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0)')
		    ->orderBy('IF (`goods`.`count` <= \'0\' AND `goods`.`isUnlimited` = \'0\', \'FIELD(`goods`.`count` DESC)\', \'FIELD()\')');

	    switch(\Yii::$app->request->get('order')){
		    case 'asc':
			    $return->addOrderBy(['`goods`.`PriceOut1`' => SORT_ASC]);
			    break;
		    case 'desc':
			    $return->addOrderBy(['`goods`.`PriceOut1`' => SORT_DESC]);
			    break;
		    case 'novinki':
			    $return->addOrderBy(['`goods`.`photodate`' => SORT_DESC]);
			    break;
		    case 'date':
		    default:
		        $return->addOrderBy(['`goods`.`tovupdate`' => SORT_DESC]);
			    break;
	    }

	    return $return;
    }

	public function getMinPrice(){
		if(empty($this->_minPrice)){
			$this->_minPrice = Good::find()
				->leftJoin('goodsgroups', '`goods`.`GroupID` = `goodsgroups`.`ID`')
				->where(['like', '`goodsgroups`.`Code`', $this->Code.'%', false])
				->andWhere(['`goodsgroups`.`enabled`' => 1])
				->andWhere('`goods`.`show_img` = 1 AND `goods`.`deleted` = 0 AND (`goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0)')
				->min('PriceOut1');
		}

		return $this->_minPrice;
	}

	public function getMaxPrice(){
		if(empty($this->_maxPrice)){
			$this->_maxPrice = Good::find()
				->leftJoin('goodsgroups', '`goods`.`GroupID` = `goodsgroups`.`ID`')
				->where(['like', '`goodsgroups`.`Code`', $this->Code.'%', false])
				->andWhere(['`goodsgroups`.`enabled`' => 1])
				->andWhere('`goods`.`show_img` = 1 AND `goods`.`deleted` = 0 AND (`goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0)')
				->max('PriceOut1');
		}

		return $this->_maxPrice;
	}

	public static function getMenu(){
		$cats = self::find()
			->select(['ID', 'Name', 'Code', 'link', 'listorder', 'enabled', 'imgSrc'])
			->orderBy('Code')
			->all();
		return self::buildTree($cats);
	}

	public function getFilters(){
		if(empty($this->_filters)){
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
					}
				}
			}

			$filterNames = array_column($filters, 'name', 'id');
			$itemVarsTemp = $itemVars;
			foreach(\Yii::$app->request->get() as $name => $value){
				$name = preg_replace('/\_/', ' ', $name);
				$id = array_search($name, $filterNames);
				$temp = [];
				if($id){
					foreach($itemVarsTemp as $keyItem => $itemVar){
						foreach(array_intersect_key($itemVar, $varChecked[$id]) as $k => $v){
							if($v){
								$temp[$keyItem] = $itemVar;
							}else{
								isset($varCount[$k]) ? $varCount[$k]++ : $varCount[$k] = 1;
							}
						}
					}
					$itemVarsTemp = $temp;
				}
			}

			foreach($itemVarsTemp as $keyItem => $itemVar){
				foreach($itemVar as $k => $v){
					isset($varCount[$k]) ? $varCount[$k]++ : $varCount[$k] = 1;
				}
			}

			foreach($filters as $go => $filter){
				foreach($filter['options'] as $gv => $val){
					$filters[$go]['options'][$gv]['count'] = isset($varCount[$gv]) ? $varCount[$gv] : 0;
				}
			}

			$this->_filters = $filters;
		}

		return $this->_filters;
	}



	public function getMetaTitle(){
		switch(\Yii::$app->request->get('order')){
			case 'asc':
				$title = $this->titleasc;
				$priceType = \Yii::t('shop', 'дешево');
				break;
			case 'desc':
				$title = $this->titledesc;
				$priceType = \Yii::t('shop', 'дорого');
				break;
			case 'novinki':
				$title = $this->titlenew;
				break;
			default:
				$title = $this->title;
				break;
		}

		if(empty($title)){
			switch(\Yii::$app->request->get('order')){
				case 'asc':
				case 'desc':
					return \Yii::t('shop',
						'{name}. Купить {priceType} оптом в интернет-магазине Krasota-Style с доставкой по Украине',
						[
							'name'      =>  $this->Name,
							'priceType' =>  $priceType
						]
					);
					break;
				default:
					return \Yii::t('shop',
						'{name}. Купить {name2} оптом недорого с доставкой по Украине - интернет-магазин Krasota Style',
						[
							'name'  =>  $this->Name,
							'name2' =>  mb_strtolower($this->Name, 'UTF-8')
						]
					);
					break;
			}
		}else{
			return strip_tags(htmlspecialchars_decode($title));
		}
	}

	public function getMetaDescription(){
		if(empty($this->descr)){
			switch(\Yii::$app->request->get('order')){
				case 'asc':
					$priceType = \Yii::t('shop', 'дешево');
					$priceType2 = \Yii::t('shop', 'от {minprice}',
						['minprice' =>  Formatter::getFormattedPrice($this->minPrice)]
					);
					$priceType3 = \Yii::t('shop', 'Скидки до 30%.');
				case 'desc':
					$priceType = empty($priceType) ?
						\Yii::t('shop', 'дорого') : $priceType;
					$priceType2 = empty($priceType2) ?
						\Yii::t('shop', 'до {maxprice}',
							['maxprice' =>  Formatter::getFormattedPrice($this->maxPrice)]
						) : $priceType2;
					$priceType3 = empty($priceType3) ?
						\Yii::t('shop', 'Возможна покупка в розницу.') : $priceType3;
					return \Yii::t('shop',
						'{name} {priceType} на сайте Krasota-Style. {count} наименований {priceType2} Специальные условия для оптовиков. {priceType3} Быстрая доставка по Украине.',
						[
							'name'          =>  $this->Name,
							'priceType'     =>  $priceType,
							'priceType2'    =>  $priceType2,
							'priceType3'    =>  $priceType3,
							'count'         =>  $this->getItems()->count()
						]
					);
					break;
				default:
					return \Yii::t('shop',
						'{name} по оптовым ценам. {count} наименований от {minprice} Специальные условия для оптовиков. Скидки до 30%. Быстрая доставка по Украине.',
						[
							'name'      =>  $this->Name,
							'minprice'  =>  Formatter::getFormattedPrice($this->minPrice),
							'count'     =>  $this->getItems()->count()
						]
					);
					break;
			}
		}else{
			return strip_tags(htmlspecialchars_decode($this->descr));
		}
	}

	public function getMetaKeywords(){
		if(empty($this->keyword)){
			switch(\Yii::$app->request->get('order')){
				case 'asc':
					$priceType = \Yii::t('shop', 'дешево, недорого, ');
				case 'desc':
					$priceType = empty($priceType) ?
						\Yii::t('shop', 'дорого, ') : $priceType;
					break;
				default:
					$priceType = '';
					break;
			}
			return \Yii::t('shop',
				'{name}, {priceType}оптом, интернет-магазин, Киев, Украина, Krasota-Style',
				[
					'name'          =>  $this->Name,
					'priceType'     =>  $priceType
				]
			);
		}else{
			return strip_tags(htmlspecialchars_decode($this->keyword));
		}
	}

}