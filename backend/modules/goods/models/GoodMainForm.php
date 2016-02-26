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

    /**
     * @type integer ID товара
     */
    public $id;

    /**
     * @type string название товара
     */
    public $name;

    /**
     * @type string ссылка на изображение товара
     */
    public $image;

    /**
     * @type string код товара
     */
    public $code;

    /**
     * @type integer штрихкод товара
     */
    public $barcode;

    /**
     * @type string артикул товара
     */
    public $additionalCode;

    /**
     * @type integer включен-ли товар
     */
    public $enabled = self::DISABLED;

    /**
     * @type string мера измерения
     */
    public $measure;

    /**
     * @type integer колличество в упаковке
     */
    public $inPackageAmount;

    /**
     * @type bool известно-ли колличество в упаковке
     */
    public $undefinedPackageAmount = false;

    /**
     * @type double оптовая цена
     */
    public $wholesalePrice;

    /**
     * @type double розничная цена
     */
    public $retailPrice;


    public $minQuantity;
    public $normalQuantity;

    /**
     * @type string описание товара
     */
    public $description;

    /**
     * @type integer ID категории товара
     */
    public $category;

    /**
     * @type double стоимость в валюте
     */
    public $anotherCurrencyValue;

    /**
     * @type bool пересчитывать товар по курсу автоматически
     */
    public $anotherCurrencyPeg = false;

    /**
     * @type string название валюты
     */
    public $anotherCurrencyTag;

    /**
     * @type integer колличество для интернет-магазина
     */
    public $count;

    /**
     * @type bool товар бесконечный
     */
    public $isUnlimited = false;

    /**
     * @type bool товар "оригинал"
     */
    public $isOriginal = false;

    /**
     * @type bool на товар есть гарантия
     */
    public $haveGuarantee = false;

    /**
     * загружает в эту модель модель Good
     *
     * @param $good Good
     */
    public function loadGood($good){
        foreach($this->modelAttributes() as $new => $old){
            $this->$new = $good->$old;
        }

        $this->undefinedPackageAmount = empty($this->inPackageAmount);
    }

    public function modelAttributes(){
        return [
            'id'            =>  'ID',
            'name'          =>  'Name',
            'description'   =>  'Description',
            'image'         =>  'ico',
            'code'          =>  'Code',
            'barcode'       =>  'BarCode1',
            'additionalCode'=>  'BarCode2',
            'enabled'       =>  'show_img',
            'measure'       =>  'Measure1',
            'category'      =>  'GroupID',
            'inPackageAmount'=> 'num_opt',
            'wholesalePrice'=>  'PriceOut1',
            'retailPrice'   =>  'PriceOut2',
        ];
    }

    /**
     * Сохраняет изменения в товар
     */
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

    /**
     * Возвращает названия аттрибутов
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'            =>  'ID',
            'name'          =>  'Название',
            'description'   =>  'Описание',
            'image'         =>  'Фото',
            'code'          =>  'Код',
            'barcode'       =>  'Штрихкод',
            'additionalCode'=>  'Добавочный код',
            'enabled'       =>  'Включен',
            'measure'       =>  'Measure1',
            'category'      =>  'Категория',
            'inPackageAmount'=> 'штук в упаковке',
            'wholesalePrice'=>  'Оптовая цена',
            'retailPrice'   =>  'Розничная цена',
            'anotherCurrencyTag'    =>  'Валюта',
            'anotherCurrencyValue'  =>  'Цена в валюте',
            'anotherCurrencyPeg'    =>  '',
        ];
    }

    /**
     * Возвращает валюты для редактирования товара
     *
     * @return array
     */
    public function getCurrencies(){
        return [
            'usd'   =>  'USD',
            'eur'   =>  'EUR'
        ];
    }
}