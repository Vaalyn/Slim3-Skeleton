<?php include_once(__DIR__ . '/../header.php'); ?>
	<main>
		<div class="row">
			<div class="col s12 m8 l4 offset-m2 offset-l4">
				<?php if (!empty($flashMessages)) : ?>
					<div class="card blue-grey darken-2">
						<div class="card-content white-text">
							<?php foreach ($flashMessages as $flashTitle => $flashMessageArray) : ?>
								<h3 class="card-title center"><?php echo htmlentities($flashTitle); ?></h3>
								<div class="divider"></div>

								<ul class="center">
									<?php foreach ($flashMessageArray as $flashMessage) : ?>
										<li><?php echo htmlentities($flashMessage); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="card blue-grey darken-2">
					<div class="card-content white-text">
						<h3 class="card-title center">Login</h3>
						<div class="divider"></div>

						<form action="login" method="post">
							<?php if (count($request->getHeader('HTTP_REFERER'))) : ?>
								<input type="hidden" name="referer" value="<?php echo $request->getHeader('HTTP_REFERER')[0]; ?>" />
							<?php endif; ?>
							<div class="row">
								<div class="input-field col s12">
									<input type="text" name="username" id="username" placeholder="Username" required>
									<label for="username">Username</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="password" name="password" id="password" placeholder="Password" required>
									<label for="password">Password</label>
								</div>
							</div>
							<div class="row">
								<div class="col s12 center">
									<input name="remember_me" id="remember-me" class="filled-in" type="checkbox" />
									<label for="remember-me">Remember Me</label>
								</div>
							</div>
							<div class="row center">
								<div class="col s12">
									<button class="btn waves-effect waves-light color-1" type="submit">Login
										<i class="material-icons right">lock_open</i>
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
<?php include_once(__DIR__ . '/../footer.php'); ?>
