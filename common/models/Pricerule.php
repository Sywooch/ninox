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

	public function asArray(){
		$parts = explode(' THEN ', $this->Formula);
		$terms = $parts['0'];
		$action = $parts['1'];
		$terms = preg_replace('/IF/', '', $terms);
		$terms = explode(' AND ', $terms);
		foreach($terms as $key => $termt){
			$termt = explode(' OR ', $termt);
			foreach($termt as $term){
				$term = preg_replace(array('/\(/', '/\)/', '/^\s/'), '', $term);
				$tTerm = explode(' = ', $term);
				if(!empty($tTerm['1'])){
					$rArray['terms'][$key][$tTerm['0']][] = array('term' => $tTerm['1'], 'type' => '=');
				}else{
					$tTerm = explode(' >= ', $term);
					if(!empty($tTerm['1'])){
						$rArray['terms'][$key][$tTerm['0']][] = array('term' => $tTerm['1'], 'type' => '>=');
					}else{
						$tTerm = explode(' <= ', $term);
						if(!empty($tTerm['1'])){
							$rArray['terms'][$key][$tTerm['0']][] = array('term' => $tTerm['1'], 'type' => '<=');
						}else{
							$tTerm = explode(' != ', $term);
							if(!empty($tTerm['1'])){
								$rArray['terms'][$key][$tTerm['0']][] = array('term' => $tTerm['1'], 'type' => '!=');
							}
						}
					}
				}
			}
		}
		$actions = explode(' AND ', $action);
		foreach($actions as $action){
			$action = explode('=', $action);
			foreach($action as $key => $row){
				$action[$key] = trim($row);
			}
			$rArray['actions'][$action['0']] = $action['1'];
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
