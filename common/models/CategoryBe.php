<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "goodsgroups_be".
 *
 * @property integer $ID
 * @property string $Name
 * @property string $Code
 * @property string $link
 * @property string $text2
 * @property string $title
 * @property string $titlenew
 * @property string $titleasc
 * @property string $titledesc
 * @property string $descr
 * @property string $keyword
 * @property string $h1asc
 * @property string $h1desc
 * @property string $h1new
 * @property string $h1
 * @property integer $menu_show
 * @property integer $listorder
 */
class CategoryBe extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goodsgroups_be';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'link'], 'required'],
            [['ID', 'menu_show', 'listorder'], 'integer'],
            [['text2', 'descr', 'keyword', 'h1asc', 'h1desc', 'h1new', 'h1'], 'string'],
            [['Name', 'Code', 'link', 'title', 'titlenew', 'titleasc', 'titledesc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Name' => 'Name',
            'Code' => 'Code',
            'link' => 'Link',
            'text2' => 'Text2',
            'title' => 'Title',
            'titlenew' => 'Titlenew',
            'titleasc' => 'Titleasc',
            'titledesc' => 'Titledesc',
            'descr' => 'Descr',
            'keyword' => 'Keyword',
            'h1asc' => 'H1asc',
            'h1desc' => 'H1desc',
            'h1new' => 'H1new',
            'h1' => 'H1',
            'menu_show' => 'Menu Show',
            'listorder' => 'Listorder',
        ];
    }
}
