<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;

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

	public function resetAction() {
		$token = $this->route_params['token'];

		$user = User::findByToken($token);

		if ($user) {
			View::render('Password/reset.php', [
				'title'	=>	'Reset Password',
				'token'	=>	$token
			]);
		} else {
			Flash::addMessage('Password reset request already expired! Try again:', Flash::WARNING);
			$this->redirect('/password/forgot');
		}
	}

	public function resetPasswordAction() {
		if (!$_POST) {
			Flash::addMessage('Access denied !', Flash::WARNING);
			$this->redirect('/');
		}

		$token = $_POST['token'];

		$user = new User($_POST);
		$user->validatePassword();

		if (!$user->errors) {
			$user = User::findByToken($token);

			if ($user) {
				if ($user->resetPassword($_POST['password'])) {
					View::render('Password/reset_success.php', [
						'title'	=>	'Success reset password'
					]);
				}
			} else {
				echo 'user not found';
			}
		} else {
			Flash::addMessage($user->errors, Flash::WARNING);
			$this->redirect('/password/reset/' . $token);
		}
	}

}
