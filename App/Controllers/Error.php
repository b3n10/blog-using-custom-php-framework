<?php

namespace App\Controllers;

use \Core\View;

class Error extends \Core\Controller {

	public function indexAction() {
		View::render('Errors/404.php', [
			'title'	=>	'404'
		]);
	}

}
