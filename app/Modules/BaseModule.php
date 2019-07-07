<?php

namespace Core\Modules;


abstract class BaseModule
{

	public abstract function name();

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