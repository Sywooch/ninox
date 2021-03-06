<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 07.12.15
 * Time: 17:07
 */

namespace backend\models;

use backend\components\S3Uploader;
use common\helpers\PriceHelper;
use common\models\GoodOptions;
use common\models\GoodOptionsValue;
use common\models\GoodOptionsVariant;
use yii\db\Query;
use yii\web\NotFoundHttpException;


class Good extends \common\models\Good{

    public function getOptions(){
        return $this->hasMany(GoodOptionsValue::className(), ['good' => 'ID'])
            ->joinWith('goodOptions');
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
     * @param string $photoLink ссылка на фото
     * @param int $order позиция
     *
     * @return bool добавлена-ли фотография
     */
    public function addPhoto($photoLink, $order = 0){
        $photo = new GoodPhoto([
            'ico'       =>  $photoLink,
            'itemid'    =>  $this->ID
        ]);

        if(!empty($order)){
            $photo->order = $order;

            foreach(GoodPhoto::find()->where(['itemid' => $this->ID])->andWhere("order >= '{$order}'")->each() as $otherPhoto){
                $otherPhoto->order = $otherPhoto->order++;
                $otherPhoto->save(false);
            }
        }

        if($photo->save(false)){
            $this->photodate = date('Y-m-d H:i:s');
            $this->tovupdate = date('Y-m-d H:i:s');
            $this->save(false);
            return true;
        }else{
            return false;
        }
    }

    /**
     * Удаляет фото
     *
     * @param int $order порядок
     *
     * @return bool
     * @throws \yii\db\StaleObjectException
     * @throws \Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function deletePhoto($order){
        $photo = GoodPhoto::findOne(['itemid' => $this->ID, 'order' => $order]);

        if(!$photo){
            throw new NotFoundHttpException('Такой фотографии не найдено!');
        }

        if($photo->delete()){
            $s3 = new S3Uploader();

            $s3->remove($photo->ico);
            $s3->remove($photo->ico, ['directory' => 'img/catalog/sm/']);

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
     * @throws \BadFunctionCallException
     * @throws \yii\web\NotFoundHttpException
     */
    public function addToOrder($order, $count = 1){
        if(empty($order)){
            throw new \BadFunctionCallException('Невозможно пользоваться данным методом, не передав заказ!');
        }

        $item = SborkaItem::findOne(['orderID' => $order->id, 'itemID' => $this->ID]);

        if(!$item){
            $item = new SborkaItem([
                'itemID'        =>  $this->ID,
                'orderID'       =>  $order->id,
                'name'          =>  $this->Name,
                'originalPrice' =>  $order->isWholesale ? $this->wholesalePrice : $this->retailPrice,
                'categoryCode'  =>  $this->categorycode
            ]);
        }

        $item->count += $count;

        return $item->save(false);
    }

    /**
     * TODO: пожалуйста, пофиксите эту хуйню через базу данных
     * @param bool $insert
     * @return bool
     */
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

        if(empty($this->p_photo)){
            $this->p_photo = '';
        }

        if(empty($this->rate) && $this->rate != 0){
            $this->rate = 0;
        }

        if(empty($this->listorder)){
            $this->listorder = (self::find()->where(['GroupID' => $this->GroupID])->max('listorder') + 1);
        }

        if(empty($this->otkl_time)){
            $this->otkl_time = '0000-00-00 00:00:00';
        }

        if(empty($this->vkl_time)){
            $this->vkl_time = '0000-00-00 00:00:00';
        }

        if(empty($this->photodate)){
            $this->photodate = '0000-00-00 00:00:00';
        }

        if(empty($this->otgruzka) && $this->otgruzka != 0){
            $this->otgruzka = '0';
        }

        if(empty($this->otgruzka_time)){
            $this->otgruzka_time = '0000-00-00 00:00:00';
        }

        if(empty($this->tovupdate)){
            $this->tovupdate = date('Y-m-d H:i:s');
        }

        if((empty($this->count) && $this->count != 0) || $this->count == null){
            $this->count = 0;
        }

        if(array_diff($this->oldAttributes, $this->attributes)){
            $this->tovupdate = date('Y-m-d H:i:s');
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function behaviors(){
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'ignored' => [
                    'Name2',
                    'ID',
                    'photodate',
                    'tovupdate',
                ],
            ]
        ];
    }

}