<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;

class Signup extends \Core\Controller {

	public function newAction() {
		View::render("Signup/new.php", [
			"title"	=>	"Signup"
		]);
	}

	public function createAction() {
		if (!$_POST) {
			View::render("Errors/404.php", [
				"title"	=>	"404 Not Found",
			]);
			exit;
		}

		$user = new User($_POST);

		if ($user->save()) {
			$user->sendActivationEmail();
			$this->redirect('/signup/success');
		} else {
			View::render("Signup/new.php", [
				"title"	=>	"Signup - Error validation",
				"user_object"	=>	$user
			]);
		}
	}

	public function successAction() {
		View::render("Signup/success.php", [
			"title"	=>	"Signup Success"
		]);
	}

	public function activateAction() {
		$token = $this->route_params['token'];

		$user = User::findByActivationToken($token);

		if ($user) {
			View::render('Signup/account-active.php', [
				'title'	=>	'Account activated !'
			]);
		} else {
			Flash::addMessage('Account already active!', Flash::WARNING);
			$this->redirect('/');
		}
	}

}
