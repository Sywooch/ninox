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
	protected $termTypes = ['=', '>=', '<=', '!='];

	public function asArray(){
		$rArray = ['terms' => [], 'actions' => []];
		$termPattern = '/'.implode('|', $this->termTypes).'/';
		$parts = explode('THEN', preg_replace('/\s/', '', $this->Formula));
		$terms = $parts[0];
		$actions = $parts[1];
		$terms = preg_replace('/IF/', '', $terms);
		$terms = explode('AND', $terms);
		foreach($terms as $key => $termt){
			$termt = explode('OR', $termt);
			foreach($termt as $term){
				$term = preg_replace('/\(|\)/', '', $term);
				preg_match($termPattern, $term, $matches);
				$tTerm = explode($matches[0], $term);
				if(!empty($tTerm[1])){
					$rArray['terms'][$key][$tTerm[0]][] = array('term' => $tTerm[1], 'type' => $matches[0]);
				}
			}
		}
		$actions = explode('AND', $actions);
		foreach($actions as $action){
			$action = explode('=', $action);
			$rArray['actions'][$action[0]] = $action[1];
		}
		return $rArray;
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
