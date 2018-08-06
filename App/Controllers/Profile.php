<?php

namespace App\Controllers;

use \Core\View;

class Profile extends Authenticated {

	public function showAction() {
		View::render('Profile/show.php', [
			'title'	=>	'Profile page'
		]);
	}

}
