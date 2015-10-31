<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 10/22/15
 * Time: 3:04 PM
 */

namespace common\helpers;


use common\models\Pricerule;
use yii\base\Component;
use yii\helpers\Json;

class PriceRuleHelper extends Component{

	public $pricerules;

	public function init(){
		$this->pricerules = Pricerule::find()->where(['Enabled' => 1])->orderBy('`Priority` DESC')->all();
	}

	public function recalc($model, $category = false){
		\Yii::trace('Model: '.Json::encode($model));
		if($model->discountType == 0 || $model->priceRuleID != 0){
			foreach($this->pricerules as $rule){
				$tmodel = self::recalcItem($model, $rule, $category);
				if($tmodel){
					return $tmodel;
				}
			}
			if($model->priceRuleID != 0){
				$model->priceModified = true;
				$model->priceRuleID = 0;
				$model->discountType = 0;
				$model->discountSize = 0;
				return $model;
			}
		}
		$model->priceModified = false;
		return $model;
	}

	private function recalcItem($model, $rule, $category){
		$ruleID = $rule->ID;
		$rule = $rule->asArray();
		$termsCount = 0;
		$discount = 0;
		foreach($rule['terms'] as $term){
			if(!empty($term['GoodGroup'])){
				$this->checkCategory($term['GoodGroup'], $model->category, $termsCount, $discount);
			}
			if($discount == $termsCount && !empty($term['Date'])){
				$this->checkDate($term['Date'], $termsCount, $discount);
			}
			if($category && $discount == $termsCount && !empty($term['WithoutBlyamba'][0]['term'])){
				$termsCount++;
			}
			if(!$category && $discount == $termsCount && !empty($term['DocumentSum'])){
				$this->checkDocumentSumm($term['DocumentSum'], $termsCount, $discount);
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
			$model->priceModified = ($model->priceRuleID != $ruleID);
			$model->priceRuleID = $ruleID;
			$model->discountType = empty($rule['actions']['Type']) ? 2 : $rule['actions']['Type'];
			$model->discountSize = $rule['actions']['Discount'];
			return $model;
		}
	}

	private function checkCategory($term, $cat, &$termsCount, &$discount){
		$termsCount++;
		foreach($term as $gg){
			if($gg['type'] == '='){
				if($cat == $gg['term']){
					$discount++;
					break;
				}
			}elseif($gg['type'] == '>='){
				if(strlen($cat) != strlen($gg['term'])){
					$cat0 = substr($cat, 0, -(strlen($cat) - strlen($gg['term'])));
				}else{
					$cat0 = $cat;
				}
				if($cat0 == $gg['term']){
					$discount++;
					break;
				}
			}elseif($gg['type'] == '<=' || $gg['type'] == '!='){
				if(strlen($cat) != strlen($gg['term'])){
					$cat0 = substr($cat, 0, -(strlen($cat) - strlen($gg['term'])));
				}else{
					$cat0 = $cat;
				}
				if($cat0 != $gg['term']){
					$discount++;
					break;
				}
			}
		}
	}

	private function checkDate($term, &$termsCount, &$discount){
		$termsCount++;
		foreach($term as $date){
			if((date('d.m.Y') == $date['term'] && $date['type'] == '=') || (time() >= strtotime($date['term']) && $date['type'] == '>=') || ($date['type'] == '<=' && time() <= strtotime($date['term']))){
				$discount++;
				break;
			}
		}
	}

	private function checkDocumentSumm($term, &$termsCount, &$discount){
		$termsCount++;
		$cartSumm = \Yii::$app->cart->cartRealSumm;
		foreach($term as $ds){
			if(($cartSumm == $ds['term'] && $ds['type'] == '=') || ($cartSumm >= $ds['term'] && $ds['type'] == '>=') || ($cartSumm <= $ds['term'] && $ds['type'] == '<=')){
				$discount += 1;
				break;
			}
		}
	}
}