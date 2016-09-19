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
            <!--<div class="about-as-header about-header semi-bold">
                <a id="about-work-header">Как мы работаем</a>
            </div>-->
            <div class="bold about-as-center">
	            Компания “Ninox” является официальным диллером немецкой компании “Citinox” в Украине.
            </div>
                <div class="about-as-order-text">
	                <span class="list-header">На нашем сайте представлен полный спектр дистанционных держателей компании Citinox:</span>
	                <ul>
		                <li>Крепления к стене</li>
		                <li>Стержневое крепление</li>
		                <li>Флажковое крепление</li>
		                <li>Тросиковое крепление</li>
		                <li>Alinox</li>
		                <li>Дополнительные детали</li>
		            </ul>
	                <p>Изделия изготавливаются из высококачественной нержавеющей стали на производстве в Германии.</p> <p>Широкое разнообразие и оригинальный эргономичный дизайн
		                креплений позволяет использовать их  при конструировании наружной рекламы различного типа и сложности.</p><p> Для защиты от злоумышленников в большинстве креплений
		                предусмотрены фиксаторы на резьбе, при помощи которых подвижная часть надежно фиксируется, и снять ее можно только с помощью специального инструмента.</p>

		                <p>Крепления идеально подойдут для удержания фасадных табличек, вывесок, фоторамок и т.д, причем как внутри, так и снаружи помещения.</p>
	                <p>К каждому товару идет детальная спецификация с указанием типоразмера. Если у вас все же возникнут вопросы,  на них с радостью ответят наши менеджеры.</p>
	                <div class="col-md-4 cta-3">
		                <span class="cta-text"><a class="btn btn-lg btn-default btn-block consult btn-price" href="https://ninox.com.ua/price.xlsx">Скачать прайс</a></span>
	                </div>
	                <div class="col-md-4 cta-3">
		                <span class="cta-text"><a class="btn btn-lg btn-default btn-block consult btn-price" href="https://ninox.com.ua/citinox-katalog.pdf">Скачать каталог</a></span>
	                </div>
                </div>
            </div>
        </div>
    </div>
</div>
