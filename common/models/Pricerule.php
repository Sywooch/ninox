<?php

namespace common\models;

use common\helpers\Formatter;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "pricerules".
 *
 * @property integer $ID
 * @property string $Name
 * @property string $Formula
 * @property integer $Enabled
 * @property integer $Priority
 */
class Pricerule extends \yii\db\ActiveRecord
{

    public $customerRule = 0;
	private $_asArray = [];
	private $_asEntity = [];
	protected $termTypes = ['=', '>=', '<=', '!='];

	public function getAsArray(){
		if(!empty($this->_asArray)){
			return $this->_asArray;
		}

		return $this->_asArray = $this->asArray();
	}

	public function getAsEntity(){
		if(!empty($this->_asEntity)){
			return $this->_asEntity;
		}

		return $this->_asEntity = $this->asEntity();
	}

	public function getTerms(){
		return $this->asArray['terms'];
	}

	public function getActions(){
		return $this->asArray['actions'];
	}
	
	public function getTermsAsEntity(){
		return $this->asEntity['terms'];
	}

	public function getActionsAsEntity(){
		return $this->asEntity['actions'];
	}

	/**
	 * Парсер ценового правила для последующего применения на фронтенде к товарам
	 * для корректной работы этот парсер лучше не трогать, особенно, если не знаешь,
	 * что именно ты трогаешь!
	 *
	 * @return mixed
	 */
	public function asArray(){
		$termPattern = '/'.implode('|', $this->termTypes).'/';
		$parts = explode('THEN', preg_replace('/\s/', '', $this->Formula));
		$result['terms'] = [];
		$result['actions'] = [];
		if(empty($parts[0]) || empty($parts[1])){
			return $result;
		}
		$terms = preg_replace('/IF/', '', $parts[0]);
		$terms = explode('AND', $terms);

		foreach($terms as $termt){
			$termt = explode('OR', $termt);
			$key = '';
			$tempArray = [];
			foreach($termt as $term){
				$term = preg_replace('/\(|\)/', '', $term);
				preg_match($termPattern, $term, $matches);
				if(!empty($matches)){
					$tTerm = explode($matches[0], $term);
					if(!empty($tTerm[1])){
						$key = $tTerm[0];
						$tempArray[] = array('value' => $tTerm[1], 'type' => $matches[0]);
					}
				}
			}
			if(!empty($key) && !empty($tempArray)){
				$result['terms'][$key][] = $tempArray;
			}
		}

		$actions = explode('AND', $parts[1]);
		foreach($actions as $action){
			$action = explode('=', $action);
			$result['actions'][$action[0]] = $action[1];
		}

		if(empty($result['actions']['Type'])){
			$result['actions']['Type'] = 2;
		}

		return $result;
	}

	public function asEntity(){
		$termPattern = '/'.implode('|', $this->termTypes).'/';
		$parts = explode('THEN', preg_replace('/\s/', '', $this->Formula));
		$result['terms'] = [];
		$result['actions'] = [];
		if(empty($parts[0]) || empty($parts[1])){
			return $result;
		}
		$terms = preg_replace('/IF/', '', $parts[0]);
		$terms = explode('AND', $terms);

		foreach($terms as $termt){
			$termt = explode('OR', $termt);
			foreach($termt as $term){
				$term = preg_replace('/\(|\)/', '', $term);
				preg_match($termPattern, $term, $matches);
				if(!empty($matches)){
					$tTerm = explode($matches[0], $term);
					if(!empty($tTerm[1]) && !empty($tTerm[0])){
						$result['terms'][] = new PriceRuleTerm(['term' => $tTerm[0], 'value' => $tTerm[1], 'type' => $matches[0]]);
					}
				}
			}
		}

		$actions = explode('AND', $parts[1]);
		foreach($actions as $action){
			$action = explode('=', $action);
			$result['actions'][$action[0]] = $action[1];
		}

		if(empty($result['actions']['Type'])){
			$result['actions']['Type'] = 2;
		}

		return $result;
	}

	/**
	 * Загрузка сущностей из формы
	 * @param $array
	 * @return bool
	 */
	public function loadEntities($array){
		$parts = [];
		$formula = '';
		$actions = '';

		foreach($array['priceRuleTerms'] as $term){
			$ruleTerm = new PriceRuleTerm();
			$ruleTerm->setAttributes($term, false);


			if($ruleTerm->validate()){
				if($ruleTerm->type == '=' || $ruleTerm->type == '>='){
					$parts[$ruleTerm->term]['OR'][] = $ruleTerm;
				}else{
					$parts[$ruleTerm->term]['AND'][] = $ruleTerm;
				}
			}
		}

		foreach($parts as $part){
			if(!empty($part['OR'])){
				$items = [];

				foreach($part['OR'] as $item){
					$items[] = $item->asString;
				}

				$itemsString = implode(' OR ', $items);

				if(count($items) > 1){
					$itemsString = "({$itemsString})";
				}

				$formula .= (empty($formula) ? '' : ' AND ').$itemsString;
			}

			if(!empty($part['AND'])){
				foreach($part['AND'] as $item){
					$formula .= (empty($formula) ? '' : ' AND ').$item->asString;
				}
			}
		}

		foreach($array['priceRuleActions'] as $key => $value){
			$actions .= (empty($actions) ? '' : ' AND ').$key.' = '.$value;
		}

		if(empty($formula) || empty($actions)){
			return false;
		}else{
			$this->Formula = 'IF '.$formula.' THEN '.$actions;
			return true;
		}
	}

	/**
	 * Возвращает персональное правило в человеко-понятной форме
	 * @return string
	 */
	public function getHumanFriendly(){
		return \Yii::t('shop', '{discount} на товары из {categoryCanBuy, plural, =0{всех категорий} =1{категории {canLinks}}
		other{категорий {canLinks}}}{categoryCantBuy, plural, =0{} =1{, кроме категории {cantLinks}}
		other{, кроме категорий {cantLinks}}}. Действует {dateOnly, select, 0{{datesFlag, select, 0{ на постоянной основе}
		other{{dateStart, select, 0{} other{c {dateStart}}} {dateEnd,select,0{} other{по {dateEnd}}}}}}
		other{только {dateOnly}}}{sum, select, 0{.} other{, при сумме заказа от {sum}}}',
			$this->ruleData);
	}

	/**
	 * Возвращает подготовленные данные ценового правила для последующей обработки
	 * и представления их на сайте в человеко-понятной форме
	 * @return array
	 */
	public function getRuleData(){
		$categoryCanBuy = $categoryCantBuy = $canLinks = [];
		$dateStart = $dateEnd = $dateOnly = $sum = $discount = 0;

		foreach($this->termsAsEntity as $term){
			if($term->term == 'GoodGroup'){
				if(in_array($term->type, ['=', '>='])){
					$categoryCanBuy[] = Html::a($term->category->name, Url::to(['/'.$term->category->link]));
				}else{
					$categoryCantBuy[] = Html::a($term->category->name, Url::to(['/'.$term->category->link]));
				}
			}
			if($term->term == 'Date'){
				switch($term->type){
					case '>=':
						$dateStart= $term->value;
						break;
					case '<=':
						$dateEnd = $term->value;
						break;
					case '=':
						$dateOnly = $term->value;
						break;
				}
			}
			if($term->term == 'DocumentSum'){
				$sum = $term->value;
			}
		}

		switch($this->actionsAsEntity['Type']){
			case 1:
				$discount = Formatter::getFormattedPrice(-$this->actionsAsEntity['Discount'], true);
				break;
			case 2:
				$discount = '-'.$this->actionsAsEntity['Discount'].'%';
				break;
			case 3:
				break;
		}

		return [
			'discount'  =>  $discount,
			'categoryCanBuy'    =>  count($categoryCanBuy),
			'categoryCantBuy'    =>  count($categoryCantBuy),
			'canLinks'  =>  implode(', ', $categoryCanBuy),
			'cantLinks'  =>  implode(', ', $categoryCantBuy),
			'dateOnly' =>  $dateOnly,
			'datesFlag' =>  $dateStart + $dateEnd,
			'dateStart' =>  $dateStart,
			'dateEnd' =>  $dateEnd,
			'sum'   =>  $sum > 0 ? Formatter::getFormattedPrice($sum) : $sum,
		];
	}

	public function getTermsByType($type){
		$terms = [];

		foreach($this->termsAsEntity as $term){
			if($term->term == $type){
				$terms[] = $term;
			}
		}

		return $terms;
	}

	/**
	 *
	 */
	public function getCategoryTerms(){
		return $this->getTermsByType('GoodGroup');
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pricerules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Enabled', 'Priority'], 'integer'],
            [['Name'], 'string', 'max' => 255],
            [['Formula'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Name' => 'Name',
            'Formula' => 'Formula',
            'Enabled' => 'Enabled',
            'Priority' => 'Priority',
        ];
    }
}
