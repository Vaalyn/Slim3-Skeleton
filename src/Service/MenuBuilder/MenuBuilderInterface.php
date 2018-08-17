<?php

declare(strict_types = 1);

namespace App\Service\MenuBuilder;

interface MenuBuilderInterface {
	/**
	 * @param string $name
	 * @param array $menuConfig
	 *
	 * @return MenuInterface
	 */
	public function buildMenuFromConfig(string $name, array $menuConfig): MenuInterface;

	/**
	 * @param string $name
	 * @param array $menuItemConfig
	 *
	 * @return MenuItemInterface
	 */
	public function buildMenuItemFromConfig(string $name, array $menuItemConfig): MenuItemInterface;
}
