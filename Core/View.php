<?php

namespace Core;

use \App\Flash;

class View {

	public static function render($file, $args = []) {
		$file = '../App/Views/' . $file;

		if (is_readable($file)) {
			// use extract to get $args array as variables
			extract($args, EXTR_SKIP);

			// add notification messages
			$notification = [];
			foreach(Flash::getMessage() as $key => $value) { $notification[$key] = htmlspecialchars($value); }

			// user object
			$user = \App\Auth::getUser();

			require_once $file;
		} else {
			throw new \Exception("File $file not found!");
		}
	}

}
