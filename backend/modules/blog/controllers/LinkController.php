<?php
/**
 * Created by PhpStorm.
 * User: krava
 * Date: 07.07.15
 * Time: 16:03
 */

namespace app\modules\blog\controllers;


use yii\web\Controller;

class LinkController extends Controller{

    private static $forAdmin = '/admin/blog/';
    private static $forImg = '//krasota-style.com.ua/img/blog/';
    private static $arrForImgResolution = [
        'big' => 'articles',
        'middle' => '130x130',
        'little' => '90x90'
    ];

    static function getForAdmin($path = ''){
        return self::$forAdmin.$path.'/';
    }

    static function getForImg($resolution = 'big'){
        return self::$forImg.self::$arrForImgResolution[$resolution].'/';
    }

}