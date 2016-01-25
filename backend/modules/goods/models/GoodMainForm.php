<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 25.01.16
 * Time: 15:54
 */

namespace backend\modules\goods\models;


use backend\models\Good;
use yii\base\Model;

class GoodMainForm extends Model{

    const ENABLED = 1;
    const DISABLED = 0;

    public $id;
    public $name;
    public $image;
    public $code;
    public $barcode;
    public $enabled = self::DISABLED;

    public $measure;
    public $inPackageAmount;

    public $wholesalePrice;
    public $retailPrice;

    public $minQuantity;
    public $normalQuantity;

    public $description;

    public $category;

    public $anotherCurrencyValue;
    public $anotherCurrencyPeg;
    public $anotherCurrencyTag;

    public $count;
    public $isUnlimited = false;
    public $isOriginal = false;
    public $haveGuarantee = false;


    public function loadGood($good){
        foreach($this->modelAttributes() as $new => $old){
            $this->$new = $good->$old;
        }
    }

    public function modelAttributes(){
        return [
            'id'            =>  'ID',
            'name'          =>  'Name',
            'description'   =>  'Description',
            'image'         =>  'ico',
            'code'          =>  'Code',
            'barcode'       =>  'BarCode1',
            'enabled'       =>  'show_img',
            'measure'       =>  'Measure1',
            'category'      =>  'GroupID',
            'inPackageAmount'=> 'num_opt',
            'wholesalePrice'=>  'PriceOut1',
            'retailPrice'   =>  'PriceOut2',
        ];
    }

    public function save(){
        $good = new Good();

        if(!empty($this->id)){
            $good = Good::findOne($this->id);
        }

        $good->Name = $this->name;
        //$good->ico = $this->image;
        $good->Code = $this->code;
        $good->BarCode1 = $this->barcode;
    }
}