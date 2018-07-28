<?php require_once dirname(__DIR__) . "/header.php"; ?>
	<?php if (isset($user->errors)): ?>
		<h3>Errors</h3>
		<ul>
			<?php foreach($user->errors as $error): ?>
			<li><?php echo htmlspecialchars($error); ?></li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
	<h1>Sign Up</h1>
	<form action="/signup/create" method="POST" class="input-forms">
		<div>
			<label for="input_name">Name:</label>
			<input type="text" id="input_name" name="name" placeholder="Name" value="<?php echo ($user) ? htmlspecialchars($user->name) : ''; ?>" autofocus required />
		</div>
		<div>
			<label for="input_email">Email Address:</label>
			<input type="email" id="input_email" name="email" placeholder="name@company" value="<?php echo ($user) ? htmlspecialchars($user->email) : ''; ?>" required />
			<span id="email_warning"></span>
		</div>
		<div>
			<label for="input_password">Password:</label>
			<input type="password" id="input_password" name="password" placeholder="Password" required />
		</div>
		<div>
			<label for="input_confirm_password">Confirm Password:</label>
			<input type="password" id="input_confirm_password" name="confirm_password" placeholder="Repeat Password" required />
		</div>
		<div>
			<button type="submit">Sign Up</button>
		</div>
	</form>
	<script src="../../js/validate-email-min.js"></script>
<?php require_once dirname(__DIR__) . "/footer.php"; ?>
