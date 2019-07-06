<?php

namespace Modules\Web;


use App\Modules\BaseModule;

class WebModule extends BaseModule
{

	public function name()
	{
		return 'web';
	}

	public function fancyName()
	{
		return 'Web Module';
	}

	public function icon()
	{
		return 'fa-globe';
	}

	public function description()
	{
		return 'The web module.';
	}

	public function hasConfig()
	{
		return true;
	}

	public function configRoute()
	{
		return route('web.configure');
	}

	public function version()
	{
		return json_decode(file_get_contents(__DIR__.'/../composer.json'))->version;
	}
}