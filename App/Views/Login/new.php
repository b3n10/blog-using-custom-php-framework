<?php require_once dirname(__DIR__) . "/header.php"; ?>

	<h1>Login</h1>
	<form action="/login/create" method="POST">
		<div>
			<label for="input_email">Email:</label>
			<input type="email" name="email" id="input_email" placeholder="name@company" value="<?php echo $email ?? ''; ?>">
		</div>
		<div>
			<label for="input_password">Password:</label>
			<input type="password" name="password" id="input_password">
		</div>
		<div>
			<label>
				<input type="checkbox" name="remember_me" <?php echo $remember_me ?? ''; ?>>Remember Me
			</label>
		</div>
		<div>
			<button type="submit">Log in</button>
			<a href="/password/forgot">Forgot password?</a>
		</div>
	</form>
<?php require_once dirname(__DIR__) . "/footer.php"; ?>
