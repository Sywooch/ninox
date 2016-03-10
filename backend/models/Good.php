<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 07.12.15
 * Time: 17:07
 */

namespace backend\models;


use common\models\Category;
use common\models\GoodOptions;
use common\models\GoodOptionsValue;
use common\models\GoodOptionsVariant;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class Good extends \common\models\Good{

    private $_options = [];
    private $_photos = [];
    private $_category = false;

    /**
     * @return GoodPhoto[]
     */
    public function getPhotos(){
        if(!empty($this->_photos)){
            return $this->_photos;
        }

        return $this->_photos = GoodPhoto::find()->where(['itemid' => $this->ID])->orderBy('order')->all();
    }

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

        return $photo->delete();
    }

    /**
     *
     * @return Category
     */
    public function getCategory(){
        if(!empty($this->_category)){
            return $this->_category;
        }

        return $this->_category = Category::findOne($this->GroupID);
    }

}