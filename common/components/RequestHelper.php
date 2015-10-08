<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.09.15
 * Time: 17:03
 */

namespace common\components;


class RequestHelper {

    public static function createGetLink($param, $value){
        $link = \Yii::$app->request->url;
        $link = preg_replace('/\?.+/', '', $link);
        $params = [];

        $tParams = \Yii::$app->request->getQueryParams();
        if(empty($value) && isset($tParams[$param])){
            unset($tParams[$param]);
        }elseif(!empty($value)){
            $tParams[$param] = $value;
        }

        foreach($tParams as $k => $v){
            $params[] = $k.'='.$v;
        }

        $link = '?'.implode('&', $params);

        /*
        if(!empty(\Yii::$app->request->get($param))){
            //сделать по-людски
            $link = preg_replace('/(|&)'.$param.'=[a-zA-Z0-9]+./', '', $link);
        }

        if(!empty($value)){
            if(preg_match('/\?/', $link)){
                $link .= $param.'='.$value;
            }else{
                $link .= '?'.$param.'='.$value;
            }
        }
         */

        return $link;
    }

}