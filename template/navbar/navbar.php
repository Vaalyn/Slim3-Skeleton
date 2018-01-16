<div class="navbar-fixed">
	<nav class="blue-grey darken-2">
		<div class="nav-wrapper container">
			<ul class="right">
				<li>
					<a href="dashboard">Dashboard</a>
				</li>
				<?php if (!$auth->check()) : ?>
					<li>
						<a href="login">Login</a>
					</li>
				<?php else : ?>
					<li>
						<a href="logout">Logout</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</nav>
</div>
