<?php

namespace Core\Modules;


abstract class BaseModule implements IModule
{



	public function fancyName()
	{
		return null;
	}

	public function icon()
	{
		return 'fa-cog';
	}

	public function description()
	{
		return null;
	}

	public abstract function hasConfig();

	public function configRoute()
	{
		return null;
	}

	public abstract function version();

}