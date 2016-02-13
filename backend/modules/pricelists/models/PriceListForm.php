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

    public $available = true;
    public $deleted = false;
    public $unlimited = false;

    public function rules(){
        return [
            [['name', 'categories', 'format'], 'required'],
            [['name'], 'string'],
            [['format'], 'integer'],
        ];
    }

    public function getFormats(){
        return [
            PriceListFeed::FORMAT_YML   =>  'yml',
            PriceListFeed::FORMAT_XML   =>  'xml'
        ];
    }

    public function attributeLabels(){
        return [
            'name'          =>  'Название',
            'categories'    =>  'Категории',
            'format'        =>  'Формат',
            'available'     =>  'Только те, что есть в наличии',
            'deleted'       =>  'Включая удалённые',
            'unlimited'     =>  'Включая безлимитные'
        ];
    }

    public function save(){
        $priceList = new PriceListFeed([
            'name'      =>  $this->name,
            'format'    =>  $this->format,
            'categories'=>  $this->categories,
            'options'   =>  [
                'available' =>  $this->available,
                'deleted'   =>  $this->deleted,
                'unlimited' =>  $this->unlimited
            ]
        ]);

        return $priceList->save(false);
    }

}