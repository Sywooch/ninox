<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "goodsoptions_categoryoptions".
 *
 * @property integer $id
 * @property integer $category
 * @property integer $option
 */
class GoodsoptionsCategoryoption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goodsoptions_categoryoptions';
    }

    public function getGoodOptions(){
        return $this->hasOne(GoodOptions::className(), ['id' => 'option'])->joinWith('optionVariants');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'option'], 'required'],
            [['category', 'option'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'option' => 'Option',
        ];
    }
}
