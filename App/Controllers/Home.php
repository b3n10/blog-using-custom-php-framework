<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Home extends \Core\Controller {

	public function indexAction() {
		View::render('Home/index.php', [
			'title'					=>	'HomePage'
		]);
	}

}
