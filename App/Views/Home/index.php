<?php require_once dirname(__DIR__) . "/header.php"; ?>
	<h1>Welcome :)</h1>
	<?php if ($user): ?>
	<p>Hi <?php echo htmlspecialchars($user->name); ?>. <a href="/logout">Log out</a></p>
	<?php else: ?>
		<a href="/signup/new">Sign up</a> or <a href="/login/">log in</a>
	<?php endif ?>
<?php require_once dirname(__DIR__) . "/footer.php"; ?>