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
	protected $termTypes = ['=', '>=', '<=', '!='];

	public function getAsArray(){
		if(!empty($this->_asArray)){
			return $this->_asArray;
		}

		return $this->_asArray = $this->asArray();
	}

	public function getTerms(){
		return $this->asArray['terms'];
	}

	public function getActions(){
		return $this->asArray['actions'];
	}

	public function setTerms($value){
		$this->_asArray['terms'] = $value;
	}

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
						$tempArray[] = array('term' => $tTerm[1], 'type' => $matches[0]);
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

		return $result;
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
