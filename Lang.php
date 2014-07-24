<?php
/**
 * Author: Ivan Pushkin
 * Email: metal@vintage.com.ua
 */
class Lang
{
	/**
	 * @static
	 *
	 * @param string $names
	 *
	 * @return array
	 */
	public static function getLanguages($names = 'Full')
	{
		return Yii::app()->params['lang'.$names];
	}

	public static function getLanguageKeys()
	{
		return array_keys(self::getLanguages());
	}

    public static function getFull(){
        $lang = self::get();
        $langs = self::getLanguages();
        return $langs[$lang];
    }

	/**
	 * @static
	 * @return string
	 */
	public static function get()
	{
		$cookies = Yii::app()->request->cookies;

		if(!isset($cookies['language']) || !$cookies['language']->value)
		{
			self::_set(self::getDefault());
			return self::getDefault();
		}
		return self::checkLang($cookies['language']->value) ? $cookies['language']->value : self::getDefault();
	}

	/**
	 * @static
	 * @return string
	 */
	public static function getCurrentTitle()
	{
		$languages = self::getLanguages();
		return $languages[Yii::app()->language];
	}

	/**
	 * @static
	 * @return string
	 */
	public static function getDefault()
	{
		return Yii::app()->params['defaultLanguage'];
	}

	/**
	 * @static
	 *
	 * @param $lang
	 */
	private static function _set($lang)
	{
		$cookie = new CHttpCookie('language', $lang);
		$cookie->httpOnly = true;
		$cookie->expire = time() + 60*60*25*30;
		Yii::app()->request->cookies['language'] = $cookie;
        Yii::app()->params['defaultLanguage'] = $lang;
	}

	/**
	 * @param $lang
	 */
	public static function update($lang)
	{
		// Language isset it get array, and value in possible array
		if(self::checkLang($lang))
		{
			self::_set($lang);
		}
		// Anyway, we have to get language and set default value
		Yii::app()->setLanguage(self::get());
	}

	/**
	 * @param $lang
	 *
	 * @return bool
	 */
	public static function checkLang($lang)
	{
		if(in_array($lang, self::getLanguageKeys(), true))
		{
			return true;
		}
		return false;
	}
}
