<?php

namespace Core\Modules;


class ModulesManager
{
	protected $loadedModules = [];

	/**
	 * @param $module
	 */
	public function register($module)
	{
		$this->loadedModules[$module] = new $module;
	}

	/**
	 * @return array
	 */
	public function getLoadedModules()
	{
		return $this->loadedModules;
	}

	/**
	 *
	 */
	public function updateDb()
	{

	}

}