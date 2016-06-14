<?php
use common\models\CategoryTranslation;

/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.10.15
 * Time: 17:25
 * @property CategoryTranslation $translate
 */

namespace frontend\models;

use common\helpers\Formatter;
use common\models\GoodOptionsValue;
use yii\data\Sort;

class Category extends \common\models\Category{

	private $_filters;
	private $_minPrice;
	private $_maxPrice;
	private $_groupIDs;

	public static function find(){
		return parent::find()->with('translations');
	}

	public function getItems(){
	    $values = $names = [];

	    foreach($this->filters as $filterArray){
		    if(!empty($filterArray['checked'])){
			    $names[] = $filterArray['name'];
			    $values = array_merge($values, $filterArray['checked']);
		    }
	    }

	    $return = Good::find()
		    ->where(['in', '`goods`.`GroupID`', $this->groupIDs])
		    ->with('reviews')
	        ->joinWith(['translations', 'photos']);

	    if(!empty($values) && !empty($names)){
		    $return
			    ->joinWith('filters')
			    ->andWhere(['in', '`goodsoptions`.`name`', $names])
			    ->andWhere(['in', '`goodsoptions_values`.`value`', $values])
			    ->groupBy('goodsoptions_values.good')
			    ->having('COUNT(goodsoptions_values.good) >= '.sizeof($names));
	    }

	    if($priceMin = \Yii::$app->request->get('minPrice')){
		    $return->andWhere(['>=', '`goods`.`PriceOut1`', $priceMin]);
	    }

	    if($priceMax = \Yii::$app->request->get('maxPrice')){
		    $return->andWhere(['<=', '`goods`.`PriceOut1`', $priceMax]);
	    }

	    $return->andWhere('`goods`.`deleted` = 0 AND (`goods`.`PriceOut1` > 0 AND `goods`.`PriceOut2` > 0)')
	        ->andWhere(['not', ['`dopfoto`.`ico`' => null]])
	        ->andWhere(['`item_translations`.`language`' => \Yii::$app->language])
	        ->andWhere(['`item_translations`.`enabled`' => 1])
		    ->groupBy(['`dopfoto`.`itemid`'])
		    ->orderBy("IF ((`goods`.`count` <= '0' AND `goods`.`isUnlimited` = '0') OR `item_translations`.`enabled` = '0', 'FIELD(`goods`.`count` DESC)', 'FIELD()')");

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
				->select('PriceOut1')
				->where(['in', 'GroupID', $this->groupIDs])
				->andWhere(['deleted' => 0])
				->andWhere('`PriceOut1` != 0 AND `PriceOut2` != 0')
				->orderBy(['PriceOut1' => SORT_ASC])
				->limit(1)->scalar();
		}

		return $this->_minPrice;
	}

	public function getMaxPrice(){
		if(empty($this->_maxPrice)){
			$this->_maxPrice = Good::find()
				->select('PriceOut1')
				->where(['in', 'GroupID', $this->groupIDs])
				->andWhere(['deleted' => 0])
				->andWhere('`PriceOut1` != 0 AND `PriceOut2` != 0')
				->orderBy(['PriceOut1' => SORT_DESC])
				->limit(1)->scalar();
		}

		return $this->_maxPrice;
	}

	public function getGroupIDs(){
		if(empty($this->_groupIDs)){
			foreach(self::find()
				->joinWith(['translations'])
				->where(['like', '`goodsgroups`.`Code`', $this->Code.'%', false])
				->andWhere(['`category_translations`.`language`' => \Yii::$app->language])
				->andWhere(['`category_translations`.`enabled`' => 1])
				->orderBy('`goodsgroups`.`Code`')
				->all() as $category){
				if($category->enabled == 1){
					if(empty($this->_groupIDs)){
						$this->_groupIDs[$category->Code] = $category->ID;
					}elseif(isset($this->_groupIDs[substr($category->Code, 0, -3)])){
						$this->_groupIDs[$category->Code] = $category->ID;
					}
				}
			}
			$this->_groupIDs = is_array($this->_groupIDs) ? array_values($this->_groupIDs) : 0;
		}

		return $this->_groupIDs;
	}

	public static function getMenu(){
		$cats = self::find()
			->joinWith(['translations'])
			->andWhere(['`category_translations`.`language`' => \Yii::$app->language])
			->andWhere(['`category_translations`.`enabled`' => 1])
			->with('photos')
			->orderBy('Code')
			->all();

		return self::buildTree($cats);
	}

	public function getFilters(){
		if(substr($this->Code, 0, 3) == 'AAB'){
			return [];
		}

		if(empty($this->_filters)){
			$filters = [];
			$varCount = [];
			$itemVars = [];
			$varChecked = [];

			foreach(GoodOptionsValue::find()
				        ->leftJoin('goods', '`goods`.`ID` = `goodsoptions_values`.`good` AND `goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0 AND `goods`.`Deleted` = 0')
				        ->where(['in', '`goods`.`GroupID`', $this->groupIDs])
				        ->with(['goodOptions', 'goodOptionsVariants'])
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

	public function getMetaName(){
		switch(\Yii::$app->request->get('order')){
			case 'asc':
				$name = empty($this->headerOrderAscending) ?
					\Yii::t('shop', 'Дешёвые {name}',['name' => mb_strtolower($this->name, 'UTF-8')]) :
					$this->headerOrderAscending;
				break;
			case 'desc':
				$name = empty($this->headerOrderDescending) ?
					\Yii::t('shop', 'Дорогие {name}',['name' => mb_strtolower($this->name, 'UTF-8')]) :
					$this->headerOrderDescending;
				break;
			case 'novinki':
				$name = empty($this->headerOrderNew) ?
					\Yii::t('shop', 'Новинки {name}',['name' => mb_strtolower($this->name, 'UTF-8')]) :
					$this->headerOrderNew;
				break;
			default:
				$name = empty($this->header) ? $this->Name : $this->header;
				break;
		}

		return strip_tags(htmlspecialchars_decode($name));
	}

	public function getMetaTitle(){
		switch(\Yii::$app->request->get('order')){
			case 'asc':
				$priceType = \Yii::t('shop', 'дешево');
				$title = $this->titleOrderAscending;
			case 'desc':
				$priceType = empty($priceType) ? \Yii::t('shop', 'дорого') : $priceType;
				$title = empty($title) ?
					(empty($this->titleOrderDescending) ?
						\Yii::t('shop',
							'{name}. Купить {priceType} оптом в интернет-магазине Krasota-Style с доставкой по Украине',
							[
								'name'      =>  $this->Name,
								'priceType' =>  $priceType
							]
						) : $this->titleOrderDescending) : $title;
				break;
			case 'novinki':
				$title = $this->titleOrderNew;
			default:
				$title = empty($title) ?
					(empty($this->title) ?
						\Yii::t('shop',
							'{name}. Купить {name2} оптом недорого с доставкой по Украине - интернет-магазин Krasota Style',
							[
								'name'  =>  $this->Name,
								'name2' =>  mb_strtolower($this->Name, 'UTF-8')
							]
						) : $this->title) : $title;
				break;
		}

		return strip_tags(htmlspecialchars_decode($title));
	}

	public function getMetaDescription($count = 0){
		if(empty($this->translation->metaDescription)){
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
							'count'         =>  empty($count) ? $this->getItems()->count() : $count
						]
					);
					break;
				default:
					return \Yii::t('shop',
						'{name} по оптовым ценам. {count} наименований от {minprice} Специальные условия для оптовиков. Скидки до 30%. Быстрая доставка по Украине.',
						[
							'name'      =>  $this->Name,
							'minprice'  =>  Formatter::getFormattedPrice($this->minPrice),
							'count'     =>  empty($count) ? $this->getItems()->count() : $count
						]
					);
					break;
			}
		}else{
			return strip_tags(htmlspecialchars_decode($this->translation->metaDescription));
		}
	}

	public function getMetaKeywords(){
		if(empty($this->translation->metaKeywords)){
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
			return strip_tags(htmlspecialchars_decode($this->translation->metaKeywords));
		}
	}

}