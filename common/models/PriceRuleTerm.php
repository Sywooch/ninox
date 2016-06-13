<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 10.06.16
 * Time: 19:26
 */

namespace common\models;


use yii\base\Model;

/**
 * @property PriceRuleTermCategory[] termCategories
 */
class PriceRuleTerm extends Model
{

    public $term;

    public $type;

    public $value;

    public function rules(){
        return [
            [['term', 'type', 'value'], 'safe']
        ];
    }

    public function getTermCategories(){
        return [
            new PriceRuleTermCategory([
                'attribute'         =>  'DocumentSum',
                'label'             =>  'Сумма заказа',
                'possibleOperands'  =>  ['>=', '<=', '=']
            ]),
            new PriceRuleTermCategory([
                'attribute'         =>  'GoodGroup',
                'label'             =>  'Категория товара',
                'possibleOperands'  =>  ['>=', '<=', '=', '!=']
            ]),
            new PriceRuleTermCategory([
                'attribute'         =>  'WithoutBlyamba',
                'label'             =>  'Без пометки "Акция"',
                'default'           =>  ['Да' => 'true'],
                'possibleOperands'  =>  ['=']
            ]),
            new PriceRuleTermCategory([
                'attribute'         =>  'Date',
                'label'             =>  'Дата',
                'possibleOperands'  =>  ['>=', '<=', '=']
            ]),
        ];
    }

    public function getCategories(){
        if($this->term == 'GoodGroup'){
            switch($this->type){
                case '>=':
                    return $this->category->childs;
                    break;
                case '=':
                case '!=':
                    return [$this->category];
                case '<=':
                    return $this->category->parents;
                    break;

            }
        }

        return false;
    }

    public function getCategory(){
        if($this->term != 'GoodGroup'){
            return false;
        }

        return Category::findOne(['Code' => $this->value]);
    }

    public function getTermCategory($category = null){
        return $this->getTermCategoryByAttribute(empty($category) ? $this->term : $category);
    }

    public function getTermCategoryByAttribute($category){
        foreach($this->termCategories as $termCategory){
            if($termCategory->attribute == $category){
                return $termCategory;
            }
        }

        return false;
    }

    public function getAsString(){
        return "({$this->term} {$this->type} {$this->value})";
    }

}