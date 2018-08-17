<?php

declare(strict_types = 1);

namespace App\Service\MenuBuilder;

use App\Service\Authentication\AuthenticationInterface;
use App\Service\Authorization\AuthorizationInterface;
use App\Service\MenuBuilder\Constants\MenuBuilderConstants;
use Slim\Interfaces\RouterInterface;

class MenuBuilder implements MenuBuilderInterface {
	/**
	 * @var RouterInterface
	 */
	protected $router;

	/**
	 * @var AuthenticationInterface
	 */
	protected $authentication;

	/**
	 * @var AuthorizationInterface
	 */
	protected $authorization;

	/**
	 * @param RouterInterface $router
	 * @param AuthenticationInterface $authentication
	 * @param AuthorizationInterface $authorization
	 */
	public function __construct(
		RouterInterface $router,
		AuthenticationInterface $authentication,
		AuthorizationInterface $authorization
	) {
		$this->router         = $router;
		$this->authentication = $authentication;
		$this->authorization  = $authorization;
	}

	/**
	 * @inheritDoc
	 */
	public function buildMenuFromConfig(string $name, array $menuConfig): MenuInterface {
		$menu = new Menu(
			$name,
			$menuConfig[MenuBuilderConstants::MENU_CONFIG_KEY_IS_LEFT],
			$menuConfig[MenuBuilderConstants::MENU_CONFIG_KEY_IS_RIGHT],
			$menuConfig[MenuBuilderConstants::MENU_CONFIG_KEY_CLASSES]
		);

		$menuItemConfigs = $menuConfig[MenuBuilderConstants::MENU_CONFIG_KEY_MENU_ITEMS];

		foreach ($menuItemConfigs as $menuItemName => $menuItemConfig) {
			$menuItem = $this->buildMenuItemFromConfig($menuItemName, $menuItemConfig);

			if (!$this->hasAccessToMenuItem($menuItem)) {
				continue;
			}

			if ($this->shouldBeHidden($menuItem)) {
				continue;
			}

			$menu->addMenuItem($menuItem);
		}

		return $menu;
	}

	/**
	 * @inheritDoc
	 */
	public function buildMenuItemFromConfig(string $name, array $menuItemConfig): MenuItemInterface {
		$menuItem = new MenuItem(
			$name,
			$menuItemConfig[MenuBuilderConstants::MENU_ITEM_CONFIG_KEY_DISPLAY_NAME],
			$menuItemConfig[MenuBuilderConstants::MENU_ITEM_CONFIG_KEY_ICON],
			$menuItemConfig[MenuBuilderConstants::MENU_ITEM_CONFIG_KEY_URL],
			$menuItemConfig[MenuBuilderConstants::MENU_ITEM_CONFIG_KEY_ROUTE_NAME],
			$menuItemConfig[MenuBuilderConstants::MENU_ITEM_CONFIG_KEY_CLASSES],
			$menuItemConfig[MenuBuilderConstants::MENU_ITEM_CONFIG_KEY_HIDE_WHEN_AUTHENTICATED] ?? false
		);

		$menuItem = $this->resolveRouteNameToUrl($menuItem);

		$menuItemConfigs = $menuItemConfig[MenuBuilderConstants::MENU_ITEM_CONFIG_KEY_MENU_ITEMS];

		foreach ($menuItemConfigs as $subMenuItemName => $subMenuItemConfig) {
			$subMenuItem = $this->buildMenuItemFromConfig($subMenuItemName, $subMenuItemConfig);

			if (!$menuItem->isDropdown()) {
				$menuItem->setIsDropdown(true);
			}

			if (!$this->hasAccessToMenuItem($subMenuItem)) {
				continue;
			}

			if ($this->shouldBeHidden($menuItem)) {
				continue;
			}

			$menuItem->addMenuItem($subMenuItem);
		}

		return $menuItem;
	}

	/**
	 * @param MenuItemInterface $menuItem
	 *
	 * @return MenuItemInterface
	 */
	protected function resolveRouteNameToUrl(MenuItemInterface $menuItem): MenuItemInterface {
		$routeName = $menuItem->getRouteName();

		if ($routeName === null) {
			return $menuItem;
		}

		$menuItem->setUrl(
			$this->router->pathFor($routeName)
		);

		return $menuItem;
	}

	/**
	 * @param MenuItemInterface $menuItem
	 *
	 * @return bool
	 */
	protected function hasAccessToMenuItem(MenuItemInterface $menuItem): bool {
		$routeName = $menuItem->getRouteName();

		if ($routeName === null) {
			return true;
		}

		if ($this->authentication->routeNeedsAuthentication($routeName) && $this->authentication->user() === null) {
			return false;
		}

		if (!$this->authorization->needsAuthorizationForRoute($routeName)) {
			return true;
		}

		return $this->hasAuthorizationForRoute($routeName);
	}

	/**
	 * @param string $routeName
	 *
	 * @return bool
	 */
	protected function hasAuthorizationForRoute(string $routeName): bool {
		return $this->authorization->hasAuthorizationForRoute(
			$this->authentication->user(),
			$routeName
		);
	}

	/**
	 * @param MenuItemInterface $menuItem
	 *
	 * @return bool
	 */
	protected function shouldBeHidden(MenuItemInterface $menuItem): bool {
		if (!$menuItem->getHideWhenAuthenticated()) {
			return false;
		}

		return $this->authentication->user() !== null;
	}
}
