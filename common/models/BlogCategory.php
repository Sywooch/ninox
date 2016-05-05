<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wp_terms".
 *
 * @property string $term_id
 * @property string $name
 * @property string $link
 * @property string $slug
 * @property integer $term_group
 * @property string $title
 * @property integer $category
 */
class BlogCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_terms';
    }

    public static function getDb(){
        return \Yii::$app->dbBlog;
    }

    public static function findByLink($link){
        return self::findOne(['link' => $link]);
    }

    public function getPosts(){
        return $this->hasMany(BlogArticle::className(), ['category' => 'term_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link', 'title'], 'string'],
            [['term_group', 'category'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'term_id' => 'Term ID',
            'name' => 'Name',
            'category' => 'Category',
            'link' => 'Link',
            'slug' => 'Slug',
            'term_group' => 'Term Group',
            'title' => 'Title',
        ];
    }
}
