<?php


namespace App\Menu;


class AppMenu
{
	protected $order = [];
	protected $menu = [];
	protected $lastSection;

	public function __construct()
	{
	}

	public static function make()
	{
		return new static();
	}

	public function section(?string $name, int $index = 0)
	{
		if (isset($this->order[$index])) {
			$last = array_key_last($this->order);
			$index = $last === 0 ? $last : $last + 1;
		}
		$this->lastSection = $this->order[$index] = $name;
		$this->menu[$name] = [];
		return $this;
	}

	public function route(string $text, string $icon, string $name, $parameters = [], $absolute = true)
	{
		if ($this->lastSection === null) {
			$this->section(null);
		}
		$this->menu[$this->lastSection] = [
			'text' => $text,
			'icon' => $icon,
			'route' => route($name, $parameters, $absolute),
		];
	}

	public function render()
	{

	}
}