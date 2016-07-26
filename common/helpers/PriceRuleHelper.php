<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 10/22/15
 * Time: 3:04 PM
 */

namespace common\helpers;

use common\models\Pricerule;
use DateTime;
use yii\base\Component;
use yii\helpers\Json;

class PriceRuleHelper extends Component{

	public $cartSumm;
	public $pricerules = [];

	public function init(){
		$this->pricerules = Pricerule::find()->where(['Enabled' => 1])->orderBy('`Priority` DESC')->all();
	}

	public function recalc(&$model, $checkOptions = ['only' => [], 'except' => ['WithoutBlyamba']]){
		if($model->discountType == 0 || $model->priceRuleID != 0){
			foreach($this->pricerules as $rule){
				if(self::recalcItem($model, $rule, $checkOptions)){
					return;
				}
			}
			if($model->priceRuleID != 0){
				$model->priceModified = true;
				$model->priceRuleID = 0;
				$model->discountType = 0;
				$model->discountSize = 0;
				$model->customerRule = 0;
				return;
			}
		}
		$model->priceModified = false;
	}

	/**
	 * @param $model
	 * @param $rule
	 * @return bool
	 */
	public function recalcSborkaItem($model, $rule){
		return $this->recalcItem($model, $rule, ['only' => ['GoodGroup']]);
	}

	protected function recalcItem(&$model, $rule, $checkOptions){
		$termsCount = 0;
		$discount = 0;
		foreach($rule->terms as $keyTerm => $terms){
			if($discount == $termsCount){
				if((empty($checkOptions['only']) && empty($checkOptions['except'])) ||
				(!empty($checkOptions['only']) && in_array($keyTerm, $checkOptions['only'])) ||
				(!empty($checkOptions['except']) && !in_array($keyTerm, $checkOptions['except']))){
					switch($keyTerm){
						case 'GoodGroup':
							$this->checkCategory($terms, $model->categoryCode, $termsCount, $discount);
							break;
						case 'Date':
							if($this->checkDate($terms, $termsCount, $discount) == 'disable'){
								$rule->Enabled = 0;
								$rule->save();
							}
							break;
						case 'WithoutBlyamba':
							$termsCount++;
							break;
						case 'DocumentSum':
							$this->checkDocumentSumm($terms, $termsCount, $discount);
							break;
						default:
							break;
					}
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
			$model->priceModified = ($model->priceRuleID != $rule->ID);
			$model->priceRuleID = $rule->ID;
			$model->discountType = $rule->actions['Type'];
			$model->discountSize = $rule->actions['Discount'];
			$model->customerRule = $rule->customerRule;
			return true;
		}
	}

	protected function checkCategory($terms, $cat, &$termsCount, &$discount){
		foreach($terms as $term){
			if($termsCount == $discount){
				$termsCount++;
				foreach($term as $gg){
					switch($gg['type']){
						case '=':
							if($cat == $gg['value']){
								$discount++;
								break 2;
							}
							break;
						case '>=':
							if(strlen($cat) != strlen($gg['value'])){
								$cat0 = substr($cat, 0, -(strlen($cat) - strlen($gg['value'])));
							}else{
								$cat0 = $cat;
							}
							if($cat0 == $gg['value']){
								$discount++;
								break 2;
							}
							break;
						case '<=':
						case '!=':
							if(strlen($cat) != strlen($gg['value'])){
								$cat0 = substr($cat, 0, -(strlen($cat) - strlen($gg['value'])));
							}else{
								$cat0 = $cat;
							}
							if($cat0 != $gg['value']){
								$discount++;
								break 2;
							}
							break;
						default:
							break;
					}
				}
			}
		}
	}

	protected function checkDate($terms, &$termsCount, &$discount){
		//\Yii::$app->setTimeZone('Europe/Kiev'); TODO: надо только, если будут использоваться другие часовые пояса
		$now = new DateTime(date('Y-m-d'));
		foreach($terms as $term){
			if($termsCount == $discount){
				$termsCount++;
				foreach($term as $date){
					$dt = new DateTime($date['value']);
					if(($date['type'] == '=' && $now->diff($dt)->days == 0) || ($date['type'] == '>=' && $dt->diff($now)->days >= 0 && $dt->diff($now)->invert == 0) || ($date['type'] == '<=' && $now->diff($dt)->days >= 0 && $now->diff($dt)->invert == 0)){
						$discount++;
						break;
					}elseif($date['type'] == '<=' && $now->diff($dt)->days >= 0 && $now->diff($dt)->invert == 1){
						return 'disable';
					}
				}
			}
		}
	}

	protected function checkDocumentSumm($terms, &$termsCount, &$discount){
		foreach($terms as $term){
			if($termsCount == $discount){
				$termsCount++;
				foreach($term as $ds){
					if(($this->cartSumm == $ds['value'] && $ds['type'] == '=') || ($this->cartSumm >= $ds['value'] && $ds['type'] == '>=') || ($this->cartSumm <= $ds['value'] && $ds['type'] == '<=')){
						$discount += 1;
						break;
					}
				}
			}
		}
	}
}