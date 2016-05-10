<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 06.05.16
 * Time: 15:40
 */

namespace frontend\modules\blog\widgets;


use common\models\BlogCategory;
use yii\base\Widget;
use yii\bootstrap\Html;

class MenuWidget extends Widget
{

    public $items = [];

    public function run(){
        return $this->renderMenu();
    }

    public function renderMenu(){
        $renderedItems = [];

        foreach($this->items as $item){
            $renderedItems[] = $this->renderItem($item);
        }

        return Html::tag('ul', implode('', $renderedItems), ['class' => 'main-menu']);
    }

    /**
     * @param BlogCategory $item
     * @param bool $subItem
     * @return string
     */
    public function renderItem($item, $subItem = false){
        $itemContent = Html::a($item->name, '/blog/'.$item->link);

        if(!$subItem && !empty($item->childs)){
            $subItems = [];

            foreach($item->childs as $child){
                $subItems[] = $this->renderItem($child, true);
            }

            $itemContent .= Html::tag('ul', implode('', $subItems));
        }

        return Html::tag('li', $itemContent);
    }

}