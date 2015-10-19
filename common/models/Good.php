<?php

namespace common\models;

use app\helpers\TranslitHelper;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "goods".
 *
 * @property integer $ID
 * @property string $ico
 * @property string $Code
 * @property string $BarCode1
 * @property string $BarCode2
 * @property string $BarCode3
 * @property string $Catalog1
 * @property string $Catalog2
 * @property string $Catalog3
 * @property string $Name
 * @property string $Name2
 * @property string $gabarity
 * @property string $shyryna
 * @property string $vysota
 * @property string $dovgyna
 * @property string $dyametr
 * @property integer $listorder
 * @property string $show_img
 * @property string $otkl_time
 * @property string $vkl_time
 * @property string $tovdate
 * @property string $orderDate
 * @property string $tovupdate
 * @property string $photodate
 * @property integer $otgruzka
 * @property string $otgruzka_time
 * @property integer $otgruzka2
 * @property string $otgruzka_time2
 * @property string $Measure1
 * @property string $Measure2
 * @property double $Ratio
 * @property string $num_opt
 * @property double $PriceIn
 * @property double $PriceOut1
 * @property double $PriceOut2
 * @property double $PriceOut3
 * @property double $PriceOut4
 * @property double $PriceOut5
 * @property double $PriceOut6
 * @property double $PriceOut7
 * @property double $PriceOut8
 * @property double $PriceOut9
 * @property double $PriceOut10
 * @property double $MinQtty
 * @property double $NormalQtty
 * @property string $Description
 * @property integer $Type
 * @property integer $IsRecipe
 * @property integer $TaxGroup
 * @property integer $IsVeryUsed
 * @property integer $GroupID
 * @property string $p_photo
 * @property integer $old_id
 * @property integer $Deleted
 * @property string $link
 * @property double $rate
 * @property integer $anotherCurrencyPeg
 * @property double $anotherCurrencyValue
 * @property string $anotherCurrencyTag
 * @property integer $supplierId
 * @property integer $garantyShow
 * @property integer $yandexExport
 * @property integer $originalGood
 * @property string $video
 * @property integer $count
 * @property integer $isUnlimited
 */
class Good extends \yii\db\ActiveRecord
{

    public $isNew = false;
    public $discount = false;
    public $isUnlimited = false;
    public $canBuy = true;
    public $priceForOneItem = false; //Вот тут я уже ахуел - сколько непонятных переменных
    public $reviewsCount = 0;

    public static function searchGoods($string, $params = []){
        if(empty($params) || $string == ''){
            return [];
        }

        $query = Good::find()->select('a.*, b.Name as categoryname')->from([Good::tableName().' a', Category::tableName().' b']);
        $terms = [];

        if(sizeof($params) > 1){
            $terms[] = 'or';
            foreach($params as $p){
                $terms[] = [
                    'like', 'a.'.$p, $string
                ];
            }
        }else{
            $terms = ['like', 'a.'.$params['0'], $string];
        }
        $query->where(['and', 'a.GroupID = b.ID', $terms]);

        $query->limit(10);

        return $query->asArray()->all();
    }

    public static function changeState($id){
        $a = Good::findOne(['ID' => $id]);
        if($a){
            $a->show_img = $a->show_img == "1" ? "0" : "1";
            $a->save(false);

            return $a->show_img;
        }

        return false;
    }

    public static function changeTrashState($id){
        $a = Good::findOne(['ID' => $id]);
        if($a){
            $a->Deleted = $a->Deleted == "1" ? "0" : "1";
            $a->save(false);

            return $a->Deleted;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ico', 'gabarity', 'shyryna', 'vysota', 'dovgyna', 'dyametr', 'listorder', 'otkl_time', 'vkl_time', 'tovdate', 'tovupdate', 'photodate', 'otgruzka', 'otgruzka_time', 'p_photo', 'link', 'rate', 'originalGood', 'video'], 'required'],
            [['listorder', 'otgruzka', 'otgruzka2', 'Type', 'IsRecipe', 'TaxGroup', 'IsVeryUsed', 'GroupID', 'old_id', 'Deleted', 'anotherCurrencyPeg', 'supplierId', 'garantyShow', 'yandexExport', 'originalGood', 'count', 'isUnlimited'], 'integer'],
            [['otkl_time', 'vkl_time', 'tovdate', 'orderDate', 'tovupdate', 'photodate', 'otgruzka_time', 'otgruzka_time2'], 'safe'],
            [['Ratio', 'PriceIn', 'PriceOut1', 'PriceOut2', 'PriceOut3', 'PriceOut4', 'PriceOut5', 'PriceOut6', 'PriceOut7', 'PriceOut8', 'PriceOut9', 'PriceOut10', 'MinQtty', 'NormalQtty', 'rate', 'anotherCurrencyValue'], 'number'],
            [['link'], 'string'],
            [['ico', 'Code', 'BarCode1', 'BarCode2', 'BarCode3', 'Catalog1', 'Catalog2', 'Catalog3', 'Name', 'Name2', 'gabarity', 'Measure1', 'Measure2', 'anotherCurrencyTag', 'video'], 'string', 'max' => 255],
            [['shyryna', 'vysota', 'dovgyna', 'dyametr'], 'string', 'max' => 20],
            [['show_img'], 'string', 'max' => 1],
            [['num_opt'], 'string', 'max' => 50],
            [['Description'], 'string', 'max' => 2550],
            [['p_photo'], 'string', 'max' => 55]
        ];
    }

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'ignored' => [
                    'Name2',
                    'ID'
                ],
            ]
        ];
    }

    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            if($this->oldAttributes['Name'] != $this->Name){
                $this->link = TranslitHelper::to($this->Name);
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'ico' => 'Фото',
            'Code' => 'Код',
            'BarCode1' => 'Штрихкод',
            'BarCode2' => 'Bar Code2',
            'BarCode3' => 'Bar Code3',
            'Catalog1' => 'Catalog1',
            'Catalog2' => 'Catalog2',
            'Catalog3' => 'Catalog3',
            'Name' => 'Название',
            'Name2' => 'Name2',
            'gabarity' => 'Габариты',
            'shyryna' => 'Ширина',
            'vysota' => 'Высота',
            'dovgyna' => 'Длина',
            'dyametr' => 'Диаметр',
            'listorder' => 'Порядок сортировки',
            'show_img' => 'Товар включён',
            'otkl_time' => 'Время отключения',
            'vkl_time' => 'Время включения',
            'tovdate' => 'Tovdate',
            'orderDate' => 'Order Date',
            'tovupdate' => 'Tovupdate',
            'photodate' => 'Photodate',
            'otgruzka' => 'Otgruzka',
            'otgruzka_time' => 'Otgruzka Time',
            'otgruzka2' => 'Otgruzka2',
            'otgruzka_time2' => 'Otgruzka Time2',
            'Measure1' => 'Measure1',
            'Measure2' => 'Measure2',
            'Ratio' => 'Ratio',
            'num_opt' => 'Num Opt',
            'PriceIn' => 'Price In',
            'PriceOut1' => 'Розничная цена',
            'PriceOut2' => 'Price Out2',
            'PriceOut3' => 'Price Out3',
            'PriceOut4' => 'Price Out4',
            'PriceOut5' => 'Price Out5',
            'PriceOut6' => 'Price Out6',
            'PriceOut7' => 'Price Out7',
            'PriceOut8' => 'Price Out8',
            'PriceOut9' => 'Price Out9',
            'PriceOut10' => 'Price Out10',
            'MinQtty' => 'Min Qtty',
            'NormalQtty' => 'Normal Qtty',
            'Description' => 'Description',
            'Type' => 'Type',
            'IsRecipe' => 'Is Recipe',
            'TaxGroup' => 'Tax Group',
            'IsVeryUsed' => 'Is Very Used',
            'GroupID' => 'Категория',
            'p_photo' => 'P Photo',
            'old_id' => 'Old ID',
            'Deleted' => 'Удалён',
            'link' => 'Ссылка',
            'rate' => 'Rate',
            'anotherCurrencyPeg' => 'Another Currency Peg',
            'anotherCurrencyValue' => 'Цена в валюте',
            'anotherCurrencyTag' => 'Валюта',
            'supplierId' => 'Supplier ID',
            'garantyShow' => 'Garanty Show',
            'yandexExport' => 'Yandex Export',
            'originalGood' => 'Original Good',
            'video' => 'Video',
            'count' => 'Количество',
            'isUnlimited' => 'Is Unlimited',
            'additionalPhoto'   =>  'Дополнительное фото'
        ];
    }
}
