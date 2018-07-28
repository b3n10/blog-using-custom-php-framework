<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Password extends \Core\Controller {

	public function forgotAction() {
		View::render('Password/forgot.php', [
			'title'	=>	'Forgot Password'
		]);
	}

	public function requestResetAction() {
		if (!$_POST) {
			\App\Flash::addMessage('Cannot Access Page!');
			$this->redirect('/');
		}

		$email = $_POST['email'] ?? false;

		if (User::sendPasswordReset($email)) {
			View::render('Password/reset_requested.php', [
				'title'	=>	'Password Reset'
			]);
		} else {
			\App\Flash::addMessage('Cannot reset password. Please try again!');
			$this->redirect('/password/forgot');
		}

	}

}
