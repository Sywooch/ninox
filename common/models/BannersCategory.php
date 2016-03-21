<?php
namespace common\models;
use Yii;
/**
 * This is the model class for table "banners_type".
 *
 * @property integer $id
 * @property string $description
 * @property string $alias
 * @property integer $sort
 * @property integer $type
 * @property integer $bg
 * @property string $category
 */
class BannersCategory extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banners_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'alias', 'category'], 'required'],
            [['description'], 'string'],
            [['sort', 'type', 'bg'], 'integer'],
            [['alias'], 'string', 'max' => 30],
            [['category'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Название',
            'alias' => 'Алиас',
            'sort' => 'Сортировка баннеров',
            'type' => 'Типы баннеров (image, html etc.) ',
            'bg' => 'Фон баннера',
            'category' => 'Категории',
        ];
    }
}