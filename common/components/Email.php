<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 29.01.16
 * Time: 17:55
 */

namespace common\components;


use common\helpers\EsputnikAPI;
use yii\base\Component;

class Email extends Component{

    public function send($data){
        return EsputnikAPI::_sendRequest($data);
    }

}