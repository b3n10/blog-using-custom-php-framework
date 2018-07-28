<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

class Posts extends Authenticated {

	public function indexAction() {
		View::render('Posts/index.php', [
			'title'	=>	'Posts'
		]);
	}

}
