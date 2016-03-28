<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sborka".
 *
 * @property integer $id
 * @property integer $itemID
 * @property integer $orderID
 * @property string $name
 * @property integer $count
 * @property integer $added
 * @property integer $nalichie
 * @property integer $zamena
 * @property integer $nezakaz
 * @property integer $vzakaz
 * @property integer $realyCount
 * @property integer $originalCount
 * @property double $originalPrice
 * @property integer $discountSize
 * @property integer $discountType
 */
class SborkaItem extends \yii\db\ActiveRecord
{

    public static $DISCOUNT_TYPES = [
        '0' =>  'Не выбрано',
        '1' =>  'Отнять сумму',
        '2' =>  'Отнять процент'
    ];

    public $price;

    public $_category;
    public $_photo;

    public function getCategory(){
        if(empty($this->_category)){
            $this->_category = Category::findOne(['Code' => $this->categoryCode]);
        }

        return $this->_category;
    }

    public function getPhoto(){
        if(empty($this->_photo)){
            $this->_photo = GoodsPhoto::find()
                ->select('ico')
                ->where(['itemid' => $this->itemID])
                ->orderBy('order DESC')
                ->scalar();
        }

        return $this->_photo;
    }

    public function afterFind(){
        parent::afterFind();

        switch($this->discountType){
            case '1':
                //Размер скидки в деньгах
                $this->price = $this->originalPrice - $this->discountSize;
                break;
            case '2':
                //Размер скидки в процентах
                $this->price = round($this->originalPrice - ($this->originalPrice / 100 * $this->discountSize), 2);
                break;
            default:
                $this->price = $this->originalPrice;
                break;
        }
    }

    public function beforeSave($insert){
        if(empty($this->realyCount)){
            $this->realyCount = 0;
        }

        if($this->isNewRecord){
            if(empty($this->added)){
                $this->added = time();
            }
            $this->originalCount = $this->count;
        }

        return parent::beforeSave(true);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sborka';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['itemID', 'orderID', 'count', 'added', 'nalichie', 'zamena', 'nezakaz', 'vzakaz', 'realyCount', 'originalCount', 'discountSize', 'discountType'], 'integer'],
            [['name', 'categoryCode'], 'string'],
            [['name', 'count', 'originalPrice'], 'required'],
            [['originalPrice'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'itemID' => 'ID товара',
            'orderID' => 'ID заказа',
            'name' => 'Название',
            'count' => 'Количество',
            'added' => 'Добавлено',
            'nalichie' => 'Наличие',
            'zamena' => 'Zamena',
            'nezakaz' => 'Не заказывать',
            'vzakaz' => 'Vzakaz',
            'realyCount' => 'Realy Count',
            'originalCount' => 'Original Count',
            'originalPrice' => 'Полная цена',
            'discountSize' => 'Размер скидки',
            'discountType' => 'Тип скидки',
            'categoryCode' => 'Код категории',
        ];
    }
}
