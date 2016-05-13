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
	public $_terms = [];
	public $_actions = [];
	protected $termTypes = ['=', '>=', '<=', '!='];

	public function __get($name){
		switch($name){
			case 'terms':
				if(!empty($this->_terms)){
					return $this->_terms;
				}

				$this->asArray();

				return $this->_terms;
				break;
			case 'actions':
				if(!empty($this->_actions)){
					return $this->_actions;
				}

				$this->asArray();

				return $this->_actions;
				break;
		}

		return parent::__get($name);
	}

	public function asArray(){
		$termPattern = '/'.implode('|', $this->termTypes).'/';
		$parts = explode('THEN', preg_replace('/\s/', '', $this->Formula));
		$terms = $parts[0];
		$actions = $parts[1];
		$terms = preg_replace('/IF/', '', $terms);
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
				$this->_terms[$key][] = $tempArray;
			}
		}

		$actions = explode('AND', $actions);
		foreach($actions as $action){
			$action = explode('=', $action);
			$this->_actions[$action[0]] = $action[1];
		}
	}

	public function getPossibleTerms(){
		return [
			'GoodGroup', 'Date', 'WithoutBlyamba', 'DocumentSum'
		];
	}

	public function getTermsPossibleValues(){
		return [
			'GoodGroup'	=>	[],
			'Date'	=>	[],
			'WithoutBlyamba'	=>	[],
			'DocumentSum'	=>	[],
		];
	}

	public function getTermPossibleValue(){

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
