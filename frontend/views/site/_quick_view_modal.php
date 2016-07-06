<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 04.07.16
 * Time: 17:31
 */

\yii\widgets\Pjax::begin([
	'id'            =>  'pjax-quick-view',
	'timeout'       =>  '5000'
]);

echo 'Hello World';

\yii\widgets\Pjax::end();