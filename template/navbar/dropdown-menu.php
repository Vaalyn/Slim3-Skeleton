<ul id="<?php echo htmlentities($menuItem->getName()); ?>-menu" class="dropdown-content z-depth-2 blue-grey darken-2">
	<?php foreach ($menuItem->getMenuItems() as $dropdownMenuItem) : ?>
		<?php require __DIR__ . '/dropdown-menu-item.php'; ?>
	<?php endforeach; ?>
</ul>
