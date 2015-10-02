<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lang".
 *
 * @property integer $id
 * @property string $url
 * @property string $local
 * @property string $name
 * @property string $shortName
 */
class Lang extends \yii\db\ActiveRecord
{

	//Переменная, для хранения текущего объекта языка
	static $current = null;

	//Получение текущего объекта языка
	static function getCurrent()
	{
		if( self::$current === null ){
			self::$current = self::getDefaultLang();
		}
		return self::$current;
	}

	//Установка текущего объекта языка и локаль пользователя
	static function setCurrent($url = null)
	{
		$language = self::getLangByUrl($url);
		self::$current = ($language === null) ? self::getDefaultLang() : $language;
		Yii::$app->language = self::$current->local;
	}

	//Получения объекта языка по умолчанию
	static function getDefaultLang()
	{
		return Lang::find()->where('`url` = :default', [':default' => 'ru'])->one();
	}

	//Получения объекта языка по буквенному идентификатору
	static function getLangByUrl($url = null)
	{
		if ($url === null) {
			return null;
		} else {
			$language = Lang::find()->where('url = :url', [':url' => $url])->one();
			if ( $language === null ) {
				return null;
			}else{
				return $language;
			}
		}
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'local', 'name', 'shortName'], 'required'],
            [['url', 'local', 'name', 'shortName'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'local' => 'Local',
            'name' => 'Name',
            'shortName' => 'Short Name',
        ];
    }
}
