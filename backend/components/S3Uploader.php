<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 21.05.16
 * Time: 12:38
 */

namespace backend\components;


use yii\base\Component;

class S3Uploader extends Component
{

    public function upload($file, $options = []){
        $options = array_merge(['name' => '', 'directory' => 'img/catalog/', 'fullReturn' => false], $options);

        if(empty($options['name'])){
            $options['name'] = $this->setName(\Yii::$app->security->generateRandomString(32).'-'.\Yii::$app->security->generateRandomString(8), $file);
        }

        $s3 = \Yii::$app->get('s3');

        $result = $s3->put($options['directory'].$options['name'], file_get_contents($file['tmp_name'][0]), null, ['ContentType' => $file['type'][0]]); //TODO: Очень внимательно с типом контента!!!!

        return $options['fullReturn'] ? $result['ObjectURL'] : $options['name'];
    }

    public function remove($file, $options = []){
        $options = array_merge(['directory' => 'img/catalog/'], $options);
        $s3 = \Yii::$app->get('s3');

        $s3->delete($options['directory'].$file);
    }

    public function setName($filename, $file) {
        preg_match('/\.[0-9a-z]{1,5}$/', $file['name'][0], $matches);
        return $filename.$matches[0];
    }

}