<?php require_once dirname(__DIR__) . "/header.php"; ?>
	<h1>Welcome :)</h1>
	<?php if ($user_object): ?>
	<p>
	Hi <?php echo htmlspecialchars($user_object->name); ?>.
	</p>
	<?php else: ?>
		<a href="/signup/new">Sign up</a> or <a href="/login/">log in</a>
	<?php endif ?>
<?php require_once dirname(__DIR__) . "/footer.php"; ?>
