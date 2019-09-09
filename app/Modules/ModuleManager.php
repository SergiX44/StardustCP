<?php

namespace Core\Modules;


class ModuleManager
{
	protected $loadedModules = [];

	/**
	 * @param $module
	 * @return BaseModule
	 */
	public function register($module)
	{
		if (isset($this->loadedModules[$module])) {
			throw new \InvalidArgumentException("The module {$module} is already registered.");
		}
		$instance = new $module;
		$this->loadedModules[$module] = $instance;
		return $instance;
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
	public function updateDatabase()
	{

		return $this;
	}

}