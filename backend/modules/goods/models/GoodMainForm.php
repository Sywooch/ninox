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
use yii\base\Object;

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
    public $inPackageAmount = 1;

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
     * Была-ли сохранена модель
     *
     * @type bool
     */
    public $isSaved = false;

    /**
     * загружает в эту модель модель Good
     *
     * @param $good Good
     */

    public $good;

    public function loadGood($good){
        foreach($this->modelAttributes() as $new => $old){
            $this->$new = $good->$old;
        }

        $this->isUnlimited = $this->isUnlimited == 1;
        $this->isOriginal = $this->isOriginal == 1;
        $this->haveGuarantee = $this->haveGuarantee == 1;

        $this->undefinedPackageAmount = empty($this->inPackageAmount);
    }

    public function modelAttributes(){
        return [
            'id'                =>  'ID',
            'name'              =>  'Name',
            'description'       =>  'Description',
            'code'              =>  'Code',
            'barcode'           =>  'BarCode1',
            'additionalCode'    =>  'BarCode2',
            'enabled'           =>  'enabled',
            'measure'           =>  'measure',
            'category'          =>  'GroupID',
            'inPackageAmount'   => 'num_opt',
            'wholesalePrice'    =>  'PriceOut1',
            'retailPrice'       =>  'PriceOut2',
            'anotherCurrencyTag'    =>  'anotherCurrencyTag',
            'anotherCurrencyPeg'    =>  'anotherCurrencyPeg',
            'anotherCurrencyValue'  =>  'anotherCurrencyValue',
            'count'             =>  'count',
            'isOriginal'        =>  'originalGood',
            'haveGuarantee'     =>  'garantyShow',
            'isUnlimited'       =>  'isUnlimited'
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

        if($this->undefinedPackageAmount){
            $this->inPackageAmount = 0;
        }

        foreach($this->modelAttributes() as $newAttribute => $oldAttribute){
            if(is_bool($this->$newAttribute)){
                $good->$oldAttribute = $this->$newAttribute ? 1 : 0;
            }else{
                $good->$oldAttribute = $this->$newAttribute;
            }
        }

        if($this->validate() && $good->save(false)){
            $this->good = $good;
            return $this->afterSave();
        }else{
            foreach($this->modelAttributes() as $newAttribute => $oldAttribute){
                $good->getErrors($oldAttribute) ? $this->addError($newAttribute, $good->getErrors($oldAttribute)[0]) : false;
            }
        }

        return false;
    }

    /**
     * Возвращает названия аттрибутов
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'                =>  'ID',
            'name'              =>  'Название',
            'description'       =>  'Описание',
            'code'              =>  'Код',
            'barcode'           =>  'Штрихкод',
            'additionalCode'    =>  'Добавочный код',
            'enabled'           =>  'Включен',
            'measure'           =>  'measure',
            'category'          =>  'Категория',
            'inPackageAmount'   => 'штук в упаковке',
            'wholesalePrice'    =>  'Оптовая цена',
            'retailPrice'       =>  'Розничная цена',
            'anotherCurrencyTag'    =>  'Валюта',
            'anotherCurrencyValue'  =>  'Цена в валюте',
            'anotherCurrencyPeg'    =>  '',
            'count'             =>  'Количество',
            'isOriginal'        =>  'Оригинальный товар',
            'haveGuarantee'     =>  'Есть гарантия',
        ];
    }

    public function rules(){
        return [
            [['inPackageAmount'], 'default', 'value' => 1],
            [['name', 'code', 'additionalCode', 'measure', 'anotherCurrencyTag'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['enabled', 'anotherCurrencyPeg', 'isOriginal', 'haveGuarantee', 'isUnlimited', 'undefinedPackageAmount'],
                'boolean'],
            [['id', 'barcode', 'category', 'inPackageAmount', 'count'], 'integer'],
            [['wholesalePrice', 'retailPrice', 'name'], 'required'],
            [['wholesalePrice', 'retailPrice', 'anotherCurrencyValue'], 'double'],
        ];
    }

    public function afterSave(){
        $this->isSaved = true;

        return true;
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