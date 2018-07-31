<?php require_once dirname(__DIR__) . "/header.php"; ?>
	<h1>Reset Password</h1>
	<form action="/password/reset-password" method="POST" class="input-forms">
		<div>
			<label for="input_password">Password:</label>
			<input type="password" id="input_password" name="password" placeholder="Password" required />
		</div>
		<div>
			<label for="input_confirm_password">Confirm Password:</label>
			<input type="password" id="input_confirm_password" name="confirm_password" placeholder="Repeat Password" required />
		</div>
		<div>
			<button type="submit">Reset</button>
		</div>
		<div>
			<input type="hidden" name="token" value=<?php echo $token ?? ''; ?>>
		</div>
	</form>
<?php require_once dirname(__DIR__) . "/footer.php"; ?>
