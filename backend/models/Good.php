<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 07.12.15
 * Time: 17:07
 */

namespace backend\models;


use common\models\Category;
use yii\web\NotFoundHttpException;

class Good extends \common\models\Good{

    public static function changeTrashState($id){
        $a = self::findOne(['ID' => $id]);

        if($a){
            $a->Deleted = $a->Deleted == "1" ? "0" : "1";
            $a->save(false);

            return $a->Deleted;
        }

        return false;
    }

    /**
     * @return GoodPhoto[]
     */
    public function getPhotos(){
        return GoodPhoto::find()->where(['itemid' => $this->ID])->orderBy('order')->all();
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
        return Category::findOne($this->GroupID);
    }

}