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

class Sms extends Component{

    public function send($data, $params = ''){
        if(is_array($params)){
            $params = implode('/', $params);
        }

        return EsputnikAPI::_sendRequest($data, 1, $params);
    }

}