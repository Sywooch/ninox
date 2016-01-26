<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 18.01.16
 * Time: 16:25
 */

namespace backend\modules\pricelists\models;


use common\models\PriceListFeed;
use yii\base\Model;

class PriceListForm extends Model{

    public $name;
    public $categories = [];
    public $format = PriceListFeed::FORMAT_YML;

    public function rules(){
        return [
            [['name', 'categories', 'format'], 'required'],
            [['name'], 'string'],
            [['format'], 'integer'],
        ];
    }

    public function save(){
        $priceList = new PriceListFeed([
            'name'      =>  $this->name,
            'format'    =>  $this->format,
            'categories'=>  $this->categories
        ]);

        return $priceList->save(false);
    }

}