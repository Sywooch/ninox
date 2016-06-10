<?php

namespace common\models;

use Yii;

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
						$result['terms'][] = array('term' => $tTerm[0], 'value' => $tTerm[1], 'type' => $matches[0]);
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
	 */
	public function loadEntities($array){
		$parts = [];
		$formula = '';
		$subRuleFormula = '';
		$actions = '';

		foreach($array['priceRuleTerms'] as $term){
			if(empty($term['term']) || empty($term['type']) || empty($term['value'])){
				return false;
			}else{
				if($term['type'] == '=' || $term['type'] == '>='){
					$parts[$term['term']]['OR'][] = $term;
				}else{
					$parts[$term['term']]['AND'][] = $term;
				}
			}
		}

		foreach($parts as $part){
			if(!empty($part['OR'])){
				foreach($part['OR'] as $item){
					$subRuleFormula .= (empty($subRuleFormula) ? '' : ' OR ').'('.$item['term'].' '.$item['type'].' '.$item['value'].')';
				}
				$formula .= (empty($formula) ? '' : 'AND ').'('.$subRuleFormula.') ';
			}

			if(!empty($part['AND'])){
				foreach($part['AND'] as $item){
					$formula .= (empty($formula) ? '' : 'AND ').'('.$item['term'].' '.$item['type'].' '.$item['value'].') ';
				}
			}
		}

		foreach($array['priceRuleActions'] as $key => $value){
			$actions .= (empty($actions) ? '' : ' AND ').$key.' = '.$value;
		}
		\Yii::trace($formula);
		\Yii::trace($actions);
		if(empty($formula) || empty($actions)){
			return false;
		}else{
			$this->Formula = 'IF '.$formula.'THEN '.$actions;
			return true;
		}
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
