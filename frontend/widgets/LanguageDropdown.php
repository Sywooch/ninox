<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 06.05.16
 * Time: 12:01
 */

namespace frontend\widgets;
use kartik\select2\Select2;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class LanguageDropdown extends Select2
{
	private static $_labels;

	private $_isError;

	public function init()
	{
		$route = Yii::$app->controller->route;
		$appLanguage = Yii::$app->language;
		$params = $_GET;
		$this->_isError = $route === Yii::$app->errorHandler->errorAction;

		array_unshift($params, '/'.$route);

		foreach (Yii::$app->urlManager->languages as $code => $language) {
			$isWildcard = substr($language, -2)==='-*';
			if (
				$language===$appLanguage ||
				// Also check for wildcard language
				$isWildcard && substr($appLanguage,0,2)===substr($language,0,2)
			) {
				continue;   // Exclude the current language
			}
			if ($isWildcard) {
				$language = substr($language,0,2);
			}
			if(is_string($code)){
				$language = $code;
			}
			$params['language'] = $language;
			$this->data[] = [
				'test' => Html::a(self::label($language), Url::to(['/', 'language' => \Yii::$app->language]))
			];
		}
		parent::init();
	}

	public function run()
	{
		// Only show this widget if we're not on the error page
		if ($this->_isError) {
			return '';
		} else {
			return parent::run();
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
