<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;
use \App\Auth;

class Profile extends Authenticated {

	public function before() {
		parent::before();
		$this->user = Auth::getUser();
	}

	public function showAction() {
		View::render('Profile/show.php', [
			'title'	=>	'Profile page'
		]);
	}

	public function editAction() {
		View::render('Profile/edit.php', [
			'title'	=>	'Edit Profile page'
		]);
	}

	public function updateAction() {
		if (!$_POST) {
			View::render("Errors/404.php", [
				"title"	=>	"404 Not Found",
			]);
			exit;
		}

		if ($this->user->updateProfile($_POST)) {
			Flash::addMessage('Changes saved');
			$this->redirect('/profile/show');
		} else {
			View::render('Profile/edit.php', [
				'title'	=>	'Profile page - validation errors',
				'user_object'	=>	$this->user
			]);
		}
	}

}
