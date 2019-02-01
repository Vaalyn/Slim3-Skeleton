<div class="navbar-fixed">
	<nav class="blue-grey darken-2">
		<div class="nav-wrapper container">
			<a href="#" data-activates="side-nav" class="button-collapse">
				<i class="material-icons">menu</i>
			</a>

			<?php $menus = $request->getAttribute('menus'); ?>

			<?php foreach ($menus as $menu) : ?>
				<?php if (in_array($menu->getName(), ['navbar_pages', 'navbar_system_pages'])) : ?>
					<?php require __DIR__ . '/menu.php' ?>
				<?php endif; ?>

				<?php if ($menu->getName() === 'navbar_pages') : ?>
					<?php require __DIR__ . '/side-nav.php'; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</nav>
</div>
