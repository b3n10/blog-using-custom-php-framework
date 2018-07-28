<?php

namespace App;

use \App\Models\User;
use \App\Models\RememberedLogin;

class Auth {

	public static function login($user, $remember_me = false) {
		session_regenerate_id(true);
		$_SESSION['user_id'] = $user->id;
		if ($remember_me) {
			if ($user->rememberLogin()) {
				setcookie('remember_me', $user->remember_me, $user->expiry, '/');
			}
		}
	}

	public static function logout() {
		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}

		// Finally, destroy the session.
		session_destroy();

		// delete cookie in db and browser
		self::forgetLogin();
	}

	public static function previousPage() {
		$_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
	}

	public static function returnToPrevPage() {
		return $_SESSION['previous_page'] ?? '/';
	}

	public static function getUser() {
		if (isset($_SESSION['user_id'])) {
			return User::findById($_SESSION['user_id']);
		} else {
			return self::getCookie();
		}
	}

	protected static function getCookie() {
		$cookie = $_COOKIE['remember_me'] ?? false;

		if ($cookie) {
			$remember_login_obj = RememberedLogin::findByToken($cookie);

			if ($remember_login_obj && !$remember_login_obj->hasExpired()) {
				$user = $remember_login_obj->getUser();
				self::login($user);

				return $user;
			}

		}
	}

	protected static function forgetLogin() {
		$cookie = $_COOKIE['remember_me'] ?? false;

		if ($cookie) {
			$remember_login_obj = RememberedLogin::findByToken($cookie);

			if ($remember_login_obj) {
				$remember_login_obj->delete();
				setcookie('remember_me', '', time() - 1);
			}
		}
	}

}
