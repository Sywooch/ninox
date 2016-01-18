<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 18.01.16
 * Time: 16:25
 */

namespace backend\modules\pricelists\models;


use common\models\PriceListFeed;
use yii\base\Model;

class PriceListForm extends Model{

    public $name;
    public $categories = [];
    public $format = PriceListFeed::FORMAT_YML;

}