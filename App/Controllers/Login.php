<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

class Login extends \Core\Controller {

	public function indexAction() {
		View::render('Login/new.php', [
			'title' => 'Login'
		]);
	}

	public function createAction() {
		if (!$_POST) {
			View::render("Errors/404.php", [
				"title"	=>	"404 Not Found",
			]);
			exit;
		}

		$user = User::authenticate($_POST['email'], $_POST['password']);

		if ($user) {
			Auth::login($user, ($_POST['remember_me'] ?? false));
			Flash::addMessage('Login successful!');
			$this->redirect(Auth::returnToPrevPage());
		} else if ($user === 0) {
			Flash::addMessage('Account not yet active! Please check your email.', Flash::WARNING);
			View::render('Login/new.php', [
				'title'				=>	'Login',
				'email'				=>	htmlspecialchars($_POST['email']),
				'remember_me'	=>	isset($_POST['remember_me']) ? 'checked' : ''
			]);
		} else {
			Flash::addMessage('Invalid email/password!', Flash::WARNING);
			View::render('Login/new.php', [
				'title'				=>	'Login',
				'email'				=>	htmlspecialchars($_POST['email']),
				'remember_me'	=>	isset($_POST['remember_me']) ? 'checked' : ''
			]);
		}
	}

	public function destroyAction() {
		Auth::logout();
		$this->redirect('/login/show-logout-message');
	}

	public function showLogoutMessageAction() {
		Flash::addMessage('Logout successful!');
		$this->redirect('/');
	}

}
