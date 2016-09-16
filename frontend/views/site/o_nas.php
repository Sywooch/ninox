<?php
use bobroid\remodal\Remodal;
use frontend\models\ReturnForm;
use yii\bootstrap\Html;
use yii\jui\Accordion;

/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 08.02.16
 * Time: 14:07
 */

$model = new \frontend\models\UsersInterestsForm();


$this->title = 'О компании';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content" xmlns="http://www.w3.org/1999/html">
    <div class="about">
        <div class="about-as padding-bottom">
            <div class="about-as-header about-header semi-bold">
                <a id="about-work-header">Как мы работаем</a>
            </div>
            <div class="bold about-as-center">
	            Компания “Ninox” является официальным диллером систем креплений наружной рекламы “Citinox” в Украине.
            </div>
                <div class="about-as-order-text">
	                <p>На нашем сайте представлен полный спектр креплений компании Citinox:</p>
	                <ul>
		                <li>Крепления к стене</li>
		                <li>Стержневое крепление</li>
		                <li>Флажковое крепление</li>
		                <li>Тросиковое крепление</li>
		                <li>Alinox</li>
		                <li>Дополнительные детали</li>
		            </ul>
	                Изделия изготавливаются из высококачественной нержавеющей стали на производстве в Германии. Широкое разнообразие и оригинальный эргономичный дизайн креплений позволяет использовать их  при конструировании наружной рекламы различного типа и сложности. Для защиты от злоумышленников в большинстве креплений предусмотрены фиксаторы на резьбе, при помощи которых подвижная часть надежно фиксируется, и снять ее можно только с помощью специального инструмента.  Крепления идеально подойдут для удержания фасадных табличек, вывесок, фоторамок и т.д, причем как внутри, так и снаружи помещения.
	                К каждому товару идет детальная спецификация с указанием типоразмера. Если у вас все же возникнут вопросы,  на них с радостью ответят наши менеджеры.
					<a href="https://ninox.com.ua/price.xlsx">Скачать прайс</a>
                </div>
            </div>
        </div>
    </div>
</div>
