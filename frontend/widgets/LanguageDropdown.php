<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 06.05.16
 * Time: 12:01
 */

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class LanguageDropdown extends Widget
{
	private static $_labels;
	private $items;
	private $_isError;

	public $links;
	public $params = [];

	public function init()
	{
		$appLanguage = Yii::$app->language;
		$this->_isError = Yii::$app->controller->route === Yii::$app->errorHandler->errorAction;

		foreach(Yii::$app->urlManager->languages as $code => $language){
			$isWildcard = substr($language, -2) === '-*';
			if($language === $appLanguage ||
				// Also check for wildcard language
				$isWildcard && substr($appLanguage, 0, 2) === substr($language, 0, 2)){
				continue;   // Exclude the current language
			}
			if($isWildcard || is_int($code)){
				$code = substr($language, 0, 2);
			}

			isset($this->links[$code]) ? $this->items[] = Html::a(self::label($code), Url::to(['/'.$this->links[$code], 'language' => $language])) : '';
		}
	}

	public function run()
	{
		// Only show this widget if we're not on the error page
		if($this->_isError){
			return '';
		}else{
			return Html::tag('div',
				Html::tag('span', self::label(substr(Yii::$app->language, 0, 2))).
				($this->items ? Html::ul($this->items, ['encode' => false]) : ''),
				$this->params
			);
		}
	}

	public static function label($code)
	{
		if (self::$_labels===null) {
			self::$_labels = [
				'ru' => Yii::t('shop', 'Рус'),
				'uk' => Yii::t('shop', 'Укр'),
				'be' => Yii::t('shop', 'Рус'),
			];
		}

		return isset(self::$_labels[$code]) ? self::$_labels[$code] : null;
	}
}
