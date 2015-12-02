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
class BannerType extends \yii\db\ActiveRecord
{
    private static $_bannersCount = [];

    public static function getList(){
        $m = self::find()->select('');
        $r = [];
        $m = $m->all();
        foreach($m as $mm){
            $r[$mm->id] = $mm->description;
        }
        return $r;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banners_type';
    }

    public static function getBannersCount(){
        if(!empty(self::$_bannersCount)){
            return self::$_bannersCount;
        }
        $a = Banner::find()->select(['count(*) as count', 'bannerTypeId as id'])->groupBy('bannerTypeId')->asArray()->all();
        $b = [];
        foreach($a as $aa){
            $b[$aa['id']] = $aa['count'];
        }
        self::$_bannersCount = $b;
        return self::$_bannersCount;
    }
    public function bannersCount(){
        if(empty(self::$_bannersCount)){
            self::getBannersCount();
        }
        return isset(self::$_bannersCount[$this->id]) ? self::$_bannersCount[$this->id] : 0;
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