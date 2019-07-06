<?php

namespace Modules\Domain;


use App\Modules\BaseModule;

class DomainModule extends BaseModule
{

	public function name()
	{
		return 'domain';
	}

	public function hasConfig()
	{
		return false;
	}

	public function version()
	{
		return json_decode(file_get_contents(__DIR__.'/../composer.json'))->version;
	}
}