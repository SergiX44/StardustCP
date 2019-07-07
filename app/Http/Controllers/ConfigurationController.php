<?php

namespace Core\Http\Controllers;

use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
	public function configuration()
	{

		$loadedModules = app('modules')->getLoadedModules();

		return view('configure.home', [
			'modules' => $loadedModules
		]);
	}
}
