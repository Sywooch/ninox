<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 02.03.16
 * Time: 13:29
 */

namespace backend\models;

/**
 * Class Category
 * @package backend\models
 * @author  Nikolai Gilko   <n.gilko@gmail.com>
 * @property Good[] $goods
 * @property Category[] $parents
 * @property Category[] $childs
 */
class Category extends \common\models\Category
{

    public function getGoods(){
        return Good::findAll(['GroupID' => $this->ID]);
    }

    public function getChilds(){
        $len = strlen($this->Code) + 3;

        return self::find()
            ->where(['like', 'Code', $this->Code.'%', false])
            ->andWhere("LENGTH(`Code`) = '{$len}'")
            ->all();
    }

}