<?php


namespace Core\Modules;


interface IModule
{
	public function name();

	public function fancyName();

	public function icon();

	public function description();

	public function hasConfig();

	public function configRoute();

	public function version();

}