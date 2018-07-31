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

			foreach(Flash::getMessage() as $key => $value) {
				if (is_array($value)) {
					$new_value = '<h3>Error</h3>';
					$new_value .= '<ul>';

					foreach ($value as $v) { $new_value .= '<li>' . htmlspecialchars($v) . '</li>'; }

					$new_value .= '</ul>';

					$notification[$key] = $new_value;
				} else {
					$notification[$key] = htmlspecialchars($value);
				}
			}

			// user object
			$user_object = \App\Auth::getUser();

			require_once $file;
		} else {
			throw new \Exception("File $file not found!");
		}
	}

}
