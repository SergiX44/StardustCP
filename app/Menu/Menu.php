<?php


namespace Core\Menu;


use Illuminate\Support\Facades\Request;
use InvalidArgumentException;

class Menu
{
	protected $order = [];
	protected $menu = [];
	protected $lastSection;
	protected $menuClasses;
	protected $sectionClasses;

	public function __construct(?string $menuClasses = null, ?string $sectionClasses = null)
	{
		$this->menuClasses = $menuClasses;
		$this->sectionClasses = $sectionClasses;
	}

	public static function make(?string $menuClasses = null, ?string $sectionClasses = null)
	{
		return new static($menuClasses, $sectionClasses);
	}

	/**
	 * Add a section to the menu
	 * @param string|null $name
	 * @param int $index
	 * @return $this
	 */
	public function section(?string $name, int $index = -1)
	{
		if (array_key_exists($name, $this->menu)) {
			$this->lastSection = $name;
		} else {
			if (array_key_exists($index, $this->order)) {
				if ($index < 0) {
					$index = min(array_keys($this->order)) - 1;
				} else {
					$index = max(array_keys($this->order)) + 1;
				}
			}
			$this->lastSection = $this->order[$index] = $name;
			$this->menu[$name] = [];
		}
		return $this;
	}

	/**
	 * Add a route to the current section
	 * @param string $text
	 * @param string $icon
	 * @param string $name
	 * @param array $parameters
	 * @param bool $absolute
	 * @return $this
	 */
	public function route(string $text, string $icon, string $name, $parameters = [], $absolute = true)
	{
		if ($this->lastSection === null) {
			$this->section(null);
		}
		$this->menu[$this->lastSection][] = [
			'text' => $text,
			'icon' => $icon,
			'route' => route($name, $parameters, $absolute),
			'route_name' => $name,
		];

		return $this;
	}

	/**
	 * @param string $text
	 * @param string $icon
	 * @param string $url
	 * @return $this
	 */
	public function url(string $text, string $icon, string $url)
	{
		if ($this->lastSection === null) {
			$this->section(null);
		}
		$this->menu[$this->lastSection][] = [
			'text' => $text,
			'icon' => $icon,
			'route' => $url,
			'route_name' => null,
		];

		return $this;
	}

	/**
	 * Add a class to the last menu entry
	 * @param string $class
	 * @return $this
	 */
	public function withClass(string $class)
	{
		if ($this->lastSection === null) {
			$this->section(null);
		}

		$index = array_key_last($this->menu[$this->lastSection]);

		if ($index === 0) {
			throw new InvalidArgumentException('The current menu entry is empty.');
		}

		$this->menu[$this->lastSection][$index]['class'] = $class;

		return $this;
	}

	/**
	 * Render the menu to HTML.
	 * @return string
	 */
	public function render()
	{
		uksort($this->order, function ($first, $second) {
			if ($first < 0 && $second < 0) {
				if ($first === $second) {
					return 0;
				}
				return $first < $second ? 1 : -1;
			} else if ($first < 0 && $second >= 0) {
				return 1;
			} else if ($second < 0 && $first >= 0) {
				return -1;
			}

			if ($first === $second) {
				return 0;
			}
			return ($first < $second) ? -1 : 1;
		});

		$lis = '';
		foreach ($this->order as $section) {
			$entries = $this->menu[$section];

			if ($this->sectionClasses === null) {
				$lis .= "<li>{$section}</li>";
			} else {
				$lis .= "<li class=\"{$this->sectionClasses}\">{$section}</li>";
			}

			foreach ($entries as $entry) {
				$title = "<span>{$entry['text']}</span>";
				$link = "<a href=\"{$entry['route']}\">{$entry['icon']}{$title}</a>";

				if (Request::routeIs($entry['route_name']) && isset($entry['class'])) {
					$lis .= "<li class=\"{$entry['class']} active\">{$link}</li>";
				} else if (Request::routeIs($entry['route_name']) && !isset($entry['class'])) {
					$lis .= "<li class=\"active\">{$link}</li>";
				} else if (isset($entry['class'])) {
					$lis .= "<li class=\"{$entry['class']}\">{$link}</li>";
				} else {
					$lis .= "<li>{$link}</li>";
				}
			}
		}

		return $this->menuClasses === null ? "<ul>{$lis}</ul>" : "<ul class=\"{$this->menuClasses}\">{$lis}</ul>";
	}
}