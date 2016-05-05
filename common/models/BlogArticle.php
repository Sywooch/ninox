<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "blog.articles".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $description
 * @property string $date
 * @property string $keywords
 * @property string $ico
 * @property integer $author
 * @property integer $commentCount
 * @property string $status
 * @property string $link
 * @property string $type
 * @property integer $show
 * @property string $publish
 * @property string $mod
 * @property double $rate
 * @property integer $views
 * @property string $video
 * @property string $future_publish
 */
class BlogArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articles';
    }

    public static function getDb(){
        return Yii::$app->dbBlog;
    }

    public function getPreview(){
        return empty($this->small_content) ? mb_substr(strip_tags($this->content), 0, 200): $this->small_content;
    }

    public static function findByLink($link){
        return self::findOne(['link' => $link]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'description', 'keywords', 'link', 'video'], 'string'],
            [['date', 'mod', 'future_publish'], 'safe'],
            [['show', 'views'], 'integer'],
            [['rate'], 'required'],
            [['rate'], 'number'],
        ];
    }

    public static function changeStateDisplay($id){
        $a = self::findOne(['id' => $id]);
        if($a){
            $a->show = ($a->show ? 0 : 1);
            $a->save(false);

            return $a->show;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'content' => 'Текст',
            'description' => 'Description',
            'date' => 'Дата добавления',
            'keywords' => 'Ключевые слова',
            'ico' => 'Картинка',
            'link' => 'Ссылка',
            'show' => 'Отображение',
            'mod' => 'Дата изменения',
            'rate' => 'Рейтинг',
            'views' => 'Количество просмотров',
            'video' => 'Видео',
            'future_publish' => 'Дата публикации',
        ];
    }
}
