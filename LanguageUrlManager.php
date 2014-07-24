<?php

class LanguageUrlManager extends CUrlManager
{
	public $languageVar = 'lang';
	public $exclude = array('gii', 'admin');

	public function createUrl($route, $params = array(), $ampersand = '&')
	{
		if(!isset($params[$this->languageVar]) || !Lang::checkLang($params[$this->languageVar]))
		{
			$params[$this->languageVar] = Lang::get();
		}

		$r = explode('/', $route);
		if(isset($r[0]) && in_array($r[0], $this->exclude))
		{
			unset($params[$this->languageVar]);
		}

		return parent::createUrl($route, $params, $ampersand);
	}

	public function parseUrl($request)
	{
		$route = parent::parseUrl($request);

        $route = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $route))));
		$requestLang = Yii::app()->request->getQuery(Yii::app()->urlManager->languageVar);

		$r = explode('/', $route);
		if(isset($r[0]) && in_array($r[0], $this->exclude))
		{
			$requestLang = 'ru';
		}
        if($route == 'site/oauth')
            $requestLang = Lang::get();

		if(is_null($requestLang) && $route != 'site/oauth')
		{
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: /' . Lang::get() . '/');
			exit();
		}

		Yii::app()->language = Lang::get();
		if(!Lang::checkLang($requestLang))
		{
			throw new CHttpException(404, t('Page not found'));
		}
		else
		{
			if(Yii::app()->language !== $requestLang)
			{
				Lang::update($requestLang);
			}
		}

		return $route;
	}
}

