<?php

namespace App\Modules;


class ModulesManager
{
	protected $loadedModules = [];

	public function register($module)
	{
		$this->loadedModules[$module] = new $module;
	}

	public function getLoadedModules()
	{
		return $this->loadedModules;
	}

	public function updateDb()
	{

	}

}