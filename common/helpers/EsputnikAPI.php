<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 27.07.15
 * Time: 15:57
 */

namespace common\helpers;

use app\models\History;
use app\models\SborkaItem;
use yii\helpers\Json;
use linslin\yii2\curl;

class EsputnikAPI {

    private static $username = 'stylekrasota@gmail.com';
    private static $password = 'f1W080be#0';
    private static $email = '';
    private static $baseURL = 'https://esputnik.com.ua/api/v1';

    public static function _sendRequest($data, $post = 1, $operation = null){
        $url = self::$baseURL."/{$data['action']}";

        if(!empty($operation)){
            $url .= "/{$operation}";
        }

        $ch = new curl\Curl();

        $ch->setOption(CURLOPT_HEADER, 1)
            ->setOption(CURLOPT_POST, $post)
            ->setOption(CURLOPT_HEADER, 1)
            ->setOption(CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json;charset=UTF-8'])
            ->setOption(CURLOPT_URL, $url)
            ->setOption(CURLOPT_USERPWD, self::$username.':'.self::$password)
            ->setOption(CURLOPT_RETURNTRANSFER, 1)
            ->setOption(CURLOPT_SSL_VERIFYPEER, false);

        if(array_key_exists('data', $data)){
            $ch->setOption(CURLOPT_POSTFIELDS, Json::encode($data['data']));
        }

        $ch->post($url);

        \Yii::trace($ch->response);

        if($ch->responseCode){
            return $ch->responseCode;
        }

        return $ch->response;
    }
    
}