<?php require_once dirname(__DIR__) . "/header.php"; ?>
	<?php if (!empty($user_object->errors)): ?>
		<h3>Errors</h3>
		<ul>
			<?php foreach($user_object->errors as $error): ?>
			<li><?php echo htmlspecialchars($error); ?></li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
	<h1>Edit Profile</h1>
	<form action="/profile/update" method="POST" class="input-forms">
		<div>
			<label for="input_name">Name:</label>
			<input type="text" id="input_name" name="name" placeholder="Name" value="<?php echo ($user_object) ? htmlspecialchars($user_object->name) : ''; ?>" required />
		</div>
		<div>
			<label for="input_email">Email Address:</label>
			<input type="email" id="input_email" name="email" placeholder="name@company" value="<?php echo ($user_object) ? htmlspecialchars($user_object->email) : ''; ?>" required />
			<span id="email_warning"></span>
		</div>
		<div>
			<label for="input_password">Password:</label>
			<input type="password" id="input_password" name="password" placeholder="Password" />
		</div>
		<div>
			<label for="input_confirm_password">Confirm Password:</label>
			<input type="password" id="input_confirm_password" name="confirm_password" placeholder="Repeat Password" />
		</div>
		<div>
			<button type="submit">Update</button>
			<a href="/profile/show">Cancel</a>
		</div>
		<div>
			<input type="hidden" name="user_id" id="user_id" value="<?php echo htmlspecialchars(\App\Auth::getUser()->id); ?>">
		</div>
	</form>
	<script src="../../js/validate-email-min.js"></script>
<?php require_once dirname(__DIR__) . "/footer.php"; ?>
