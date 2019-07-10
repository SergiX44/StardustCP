<?php

namespace Modules\Database;


use Core\Modules\BaseModule;
use function Safe\file_get_contents;
use function Safe\json_decode;

class DatabaseModule extends BaseModule
{

	/**
	 * @return string
	 */
	public function name()
	{
		return 'database';
	}

	/**
	 * @return null|string
	 */
	public function fancyName()
	{
		return 'Database Module';
	}

	public function icon()
	{
		return 'fa-database';
	}

	/**
	 * @return null|string
	 */
	public function description()
	{
		return 'The database module.';
	}

	/**
	 * @return bool
	 */
	public function hasConfig()
	{
		return true;
	}

	/**
	 * @return null|string
	 */
	public function configRoute()
	{
		return route('database.configure');
	}

	/**
	 * @return mixed
	 * @throws \Safe\Exceptions\FilesystemException
	 * @throws \Safe\Exceptions\JsonException
	 */
	public function version()
	{
		return json_decode(file_get_contents(__DIR__.'/../composer.json'))->version;
	}
}