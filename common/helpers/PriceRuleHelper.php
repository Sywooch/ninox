<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 10/22/15
 * Time: 3:04 PM
 */

namespace common\helpers;

use DateTime;
use yii\base\Component;

class PriceRuleHelper extends Component{

	public $cartSumm;

	public function recalc($model, $category = false){
		if($model->discountType == 0 || $model->priceRuleID != 0){
			foreach($this->pricerules as $rule){
				$tmodel = $this->recalcItem($model, $rule, $category);
				if($tmodel){
					return $tmodel;
				}
			}
			if($model->priceRuleID != 0){
				$model->priceModified = true;
				$model->priceRuleID = 0;
				$model->discountType = 0;
				$model->discountSize = 0;
				$model->customerRule = 0;
				return $model;
			}
		}
		$model->priceModified = false;
		return $model;
	}

	public function recalcSborkaItem($model, $rule){
		return $this->recalcItem($model, $rule, false);
	}

	private function recalcItem($model, $rule, $category){
		$termsCount = 0;
		$discount = 0;
		foreach($rule->terms as $keyTerm => $term){
			if($discount == $termsCount){
				switch($keyTerm){
					case 'GoodGroup':
						$this->checkCategory($term, $model->category, $termsCount, $discount);
						break;
					case 'Date':
						$this->checkDate($term, $termsCount, $discount);
						break;
					case 'WithoutBlyamba':
						if($category && !empty($term[0]['term'])){
							$termsCount++;
						}
						break;
					case 'DocumentSum':
						if(!$category){
							$this->checkDocumentSumm($term, $termsCount, $discount);
						}
						break;
					default:
						break;
				}
			}else{
				break;
			}
			/*if($discount == $termsCount && !empty($term['ItemPrice'])){
				$termsCount++;
				foreach($term['ItemPrice'] as $ip){
					if(($itemInfo['PriceOut1'] == $ip['term'] && $ip['type'] == '=') || ($itemInfo['PriceOut1'] >= $ip['term'] && $ip['type'] == '>=') || ($itemInfo['PriceOut1'] <= $ip['term'] && $ip['type'] == '<=')){
						$discount += 1;
						break;
					}
				}
			}
			if($discount == $termsCount && !empty($term['ItemCount'])){
				$termsCount++;
				if(!isset($cartInfo[$key])){
					$cartInfo[$key]['flag'] = false;
				}
				if(!isset($cartInfo[$key]['items'][$i])){
					$cartInfo[$key]['items'][$i] = $i;
					$cartInfo[$key]['count'] += $itemInfo['count'];
				}
				foreach($term['ItemCount'] as $ic){
					if(($cartInfo[$key]['count'] == $ic['term'] && $ic['type'] == '=') || ($cartInfo[$key]['count'] >= $ic['term'] && $ic['type'] == '>=') || ($cartInfo[$key]['count'] <= $ic['term'] && $ic['type'] == '<=')){
						$discount += 1;
						break;
					}
				}
			}
		}

		if($discount == $termsCount && $termsCount != 0 && isset($cartInfo[$key]['flag']) && !$cartInfo[$key]['flag']){
			foreach($cartInfo[$key]['items'] as $item){
				if($item != $i){
					$itemPrices = array(
						'oldPrices' => array(
							'PriceOut1'     => $returnArray[$item]['PriceOut1'] + 0,
							'PriceOut2'     => $returnArray[$item]['PriceOut2'] + 0,
							'priceForOne'   => $returnArray[$item][$key2] + 0,
							'fullPrice'     => $returnArray[$item]['fullPrice'] + 0
						),
						'PriceOut1' => round(($returnArray[$item]['PriceOut1'] - ($priceRule['actions']['Type'] == 1 ? $priceRule['actions']['Discount'] : (($returnArray[$item]['PriceOut1'] / 100) * $priceRule['actions']['Discount']))), 2),
						'PriceOut2' => round(($returnArray[$item]['PriceOut2'] - ($priceRule['actions']['Type'] == 1 ? $priceRule['actions']['Discount'] : (($returnArray[$item]['PriceOut2'] / 100) * $priceRule['actions']['Discount']))), 2),
						'PriceWithCount' => round(($returnArray[$item]['fullPrice'] - ($priceRule['actions']['Type'] == 1 ? ($priceRule['actions']['Discount'] * $returnArray[$item]['count']) : (($returnArray[$item]['fullPrice'] / 100) * $priceRule['actions']['Discount']))), 2),
						'discount' => $priceRule['actions']['Discount'], //TODO: remove it's
						'discountSize' => $priceRule['actions']['Discount'],
						'discountType' => $priceRule['actions']['Type'],
						'priceRuleID' => $key
					);
					$returnArray[$item] = array(
						'goodID' 		=> $returnArray[$item]['goodID'],
						'count' 		=> $returnArray[$item]['count'],
						'name' 			=> $returnArray[$item]['name'],
						'photo' 		=> $returnArray[$item]['photo'],
						'show_img' 		=> $returnArray[$item]['show_img'],
						'PriceOut1' 	=> $itemPrices['PriceOut1'] + 0,
						'PriceOut2' 	=> $itemPrices['PriceOut2'] + 0,
						'priceForOne' 	=> $itemPrices[$key2] + 0,
						'fullPrice' 	=> $itemPrices['PriceWithCount'],
						'countInStore' 	=> $returnArray[$item]['countInStore'],
						'countInCart' 	=> $returnArray[$item]['countInCart'],
						'discount' 		=> $itemPrices['discount'] > 0 ? $itemPrices['discount'] : false,
						'discountSize'  => $itemPrices['discountSize'],
						'discountType'  => $itemPrices['discountType'],
						'priceRuleID'   => $itemPrices['priceRuleID'],
						'supplierId'	=> $returnArray[$item]['supplierId']
					);

					if($itemPrices['oldPrices'] != ''){
						$returnArray[$item]['oldPrices'] = $itemPrices['oldPrices'];
					}
				}
			}
			$cartInfo[$key]['flag'] = true;*/
		}
		if($discount == $termsCount && $termsCount != 0){
			\Yii::trace('Model: '.$model->priceRuleID.'; ID: '.$rule->ID);
			$model->priceModified = ($model->priceRuleID != $rule->ID);
			$model->priceRuleID = $rule->ID;
			$model->discountType = empty($rule->actions['Type']) ? 2 : $rule->actions['Type'];
			$model->discountSize = $rule->actions['Discount'];
			$model->customerRule = $rule->customerRule;
			return $model;
		}
	}

	private function checkCategory($term, $cat, &$termsCount, &$discount){
		$termsCount++;
		foreach($term as $gg){
			switch($gg['type']){
				case '=':
					if($cat == $gg['term']){
						$discount++;
						return;
					}
					break;
				case '>=':
					if(strlen($cat) != strlen($gg['term'])){
						$cat0 = substr($cat, 0, -(strlen($cat) - strlen($gg['term'])));
					}else{
						$cat0 = $cat;
					}
					if($cat0 == $gg['term']){
						$discount++;
						return;
					}
					break;
				case '<=':
				case '!=':
					if(strlen($cat) != strlen($gg['term'])){
						$cat0 = substr($cat, 0, -(strlen($cat) - strlen($gg['term'])));
					}else{
						$cat0 = $cat;
					}
					if($cat0 != $gg['term']){
						$discount++;
						return;
					}
					break;
				default:
					break;
			}
		}
	}

	private function checkDate($term, &$termsCount, &$discount){
		$termsCount++;
		date_default_timezone_set('Europe/Kiev');
		$now = new DateTime(date('Y-m-d'));
		foreach($term as $date){
			$dt = new DateTime($date['term']);
			if(($date['type'] == '=' && $now->diff($dt)->days == 0) || ($date['type'] == '>=' && $dt->diff($now)->days >= 0 && $dt->diff($now)->invert == 0) || ($date['type'] == '<=' && $now->diff($dt)->days >= 0 && $now->diff($dt)->invert == 0)){
				$discount++;
				break;
			}
		}
	}

	private function checkDocumentSumm($term, &$termsCount, &$discount){
		$termsCount++;
		$cartSumm = !empty($this->cartSumm) ? $this->cartSumm : \Yii::$app->cart->cartRealSumm;
		foreach($term as $ds){
			if(($cartSumm == $ds['term'] && $ds['type'] == '=') || ($cartSumm >= $ds['term'] && $ds['type'] == '>=') || ($cartSumm <= $ds['term'] && $ds['type'] == '<=')){
				$discount += 1;
				break;
			}
		}
	}
}