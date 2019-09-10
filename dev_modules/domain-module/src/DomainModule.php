<?php

namespace Modules\Domain;


use Core\Modules\BaseModule;
use function Safe\file_get_contents;
use function Safe\json_decode;

class DomainModule extends BaseModule
{

	/**
	 * @return string
	 */
	public function name()
	{
		return 'domain';
	}

	/**
	 * @return bool
	 */
	public function hasConfig()
	{
		return false;
	}

	/**
	 * @return mixed
	 * @throws \Safe\Exceptions\FilesystemException
	 * @throws \Safe\Exceptions\JsonException
	 */
	public function version()
	{
		return json_decode(file_get_contents(__DIR__ . '/../composer.json'))->version;
	}
}