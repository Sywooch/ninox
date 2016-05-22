<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 07.12.15
 * Time: 17:07
 */

namespace backend\models;

use backend\components\S3Uploader;
use common\helpers\TranslitHelper;
use common\models\GoodOptions;
use common\models\GoodOptionsValue;
use common\models\GoodOptionsVariant;
use common\models\GoodsPhoto;
use yii\db\Query;
use yii\web\NotFoundHttpException;


class Good extends \common\models\Good{

    private $_options = [];

    public function getOptions($updateCache = false){
        if(!empty($this->_options) && !$updateCache){
            return $this->_options;
        }

        $query = Query::create(new Query())
            ->select([
                'goodsoptions.name as option',
                'goodsoptions.id as optionID',
                'goodsoptions_variants.value as value',
                'goodsoptions_variants.id as valueID'
            ])
            ->from(GoodOptionsValue::tableName().' goodsoptions_values')
            ->leftJoin(GoodOptionsVariant::tableName().' goodsoptions_variants', 'goodsoptions_values.value = goodsoptions_variants.id')
            ->leftJoin(GoodOptions::tableName().' goodsoptions', 'goodsoptions_values.option = goodsoptions.id')
            ->where(['goodsoptions_values.good' => $this->ID]);

        return $this->_options = $query->all();
    }

    public function getCategory(){
        return $this->hasOne(Category::className(), ['ID' => 'GroupID']);
    }

    public function setDeleted($val){
        $this->Deleted = $val;
    }

    public function getDeleted(){
        return $this->Deleted;
    }

    /**
     * Добавляет фото
     *
     * @param string $photo ссылка на фото
     * @param int $order позиция
     *
     * @return bool добавлена-ли фотография
     */
    public function addPhoto($photo, $order = 0){
        $photo = new GoodPhoto([
            'ico'       =>  $photo,
            'itemid'    =>  $this->ID
        ]);

        if(!empty($order)){
            $photo->order = $order;

            foreach(GoodPhoto::find()->where(['itemid' => $this->ID])->andWhere("order >= '{$order}'")->each() as $otherPhoto){
                $otherPhoto->order = $otherPhoto->order++;
                $otherPhoto->save(false);
            }
        }


        return $photo->save(false);
    }

    /**
     * Удаляет фото
     *
     * @param int $order порядок
     *
     * @return bool
     * @throws \Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function deletePhoto($order){
        $photo = GoodPhoto::findOne(['itemid' => $this->ID, 'order' => $order]);

        if(!$photo){
            throw new NotFoundHttpException("Такой фотографии не найдено!");
        }

        if($photo->delete()){
            $s3 = new S3Uploader();

            $s3->remove($photo->ico);

            return true;
        }

        return false;
    }

    /**
     * Добавляет товар в заказ
     * @param $order History Модель заказа
     * @param $count integer Колличество
     *
     * @return bool Добавлен-ли товар в заказ
     * @throws \yii\web\NotFoundHttpException
     */
    public function addToOrder($order, $count = 1){
        if(empty($order)){
            throw new \BadFunctionCallException("Невозможно пользоваться данным методом, не передав заказ!");
        }

        $item = SborkaItem::findOne(['orderID' => $order->id, 'itemID' => $this->ID]);

        if(!$item){
            $item = new SborkaItem([
                'itemID'        =>  $this->ID,
                'orderID'       =>  $order->id,
                'name'          =>  $this->Name,
                'originalPrice' =>  $order->isWholesale() ? $this->wholesalePrice : $this->retailPrice,
                'categoryCode'  =>  $this->categorycode
            ]);
        }

        $item->count += $count;

        return $item->save(false);
    }

    public function beforeSave($insert)
    {
        if(empty($this->dimensions)){
            $this->dimensions = '';
        }

        if(empty($this->width)){
            $this->width = '';
        }

        if(empty($this->height)){
            $this->height = '';
        }

        if(empty($this->length)){
            $this->length = '';
        }

        if(empty($this->diameter)){
            $this->diameter = '';
        }

        if(empty($this->listorder)){
            $this->listorder = (self::find()->where(['GroupID' => $this->GroupID])->max("listorder") + 1);
        }

        if(empty($this->otkl_time)){
            $this->otkl_time = '0000-00-00 00:00:00';
        }

        if(empty($this->vkl_time)){
            $this->vkl_time = '0000-00-00 00:00:00';
        }

        if(empty($this->tovdate)){
            $this->tovdate = '0000-00-00 00:00:00';
        }

        if(empty($this->photodate)){
            $this->photodate = '0000-00-00 00:00:00';
        }

        if(empty($this->otgruzka)){
            $this->otgruzka = '0000-00-00 00:00:00';
        }

        if(empty($this->otgruzka_time)){
            $this->otgruzka_time = '0000-00-00 00:00:00';
        }

        $this->tovupdate = date('Y-m-d H:i:s');

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function behaviors(){
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

}