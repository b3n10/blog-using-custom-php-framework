<?php require_once dirname(__DIR__) . "/header.php"; ?>
	<h1>User Profile</h1>
	<dl>
		<dt>Name:</dt>
		<dd><?php echo htmlspecialchars($user_object->name); ?></dd>
		<dt>Email:</dt>
		<dd><?php echo htmlspecialchars($user_object->email); ?></dd>
	</dl>
	<a href="/profile/edit">Edit profile</a>
<?php require_once dirname(__DIR__) . "/footer.php"; ?>
