<?php require_once dirname(__DIR__) . "/header.php"; ?>
	<h1>Reset Password</h1>
	<form action="/password/request-reset" method="POST">
		<div>
			<label for="inputEmail">Email Address:</label>
			<input type="email" name="email" placeholder="name@domain" autofocus required>
		</div>
		<div>
			<button type="submit">Send Password Reset</button>
		</div>
	</form>
<?php require_once dirname(__DIR__) . "/footer.php"; ?>
