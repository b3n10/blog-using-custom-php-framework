<?php

namespace App;

class Flash {

	const SUCCESS = 'success';
	const INFO = 'info';
	const WARNING = 'warning';

	public static function addMessage($msg = '', $type = 'success') {
		$_SESSION['notification'] = [
			'body'	=>	$msg,
			'type'	=>	$type
		];
	}

	public static function getMessage() {
		if (isset($_SESSION['notification'])) {
			$notification = $_SESSION['notification'];
			unset($_SESSION['notification']);
			return $notification;
		}
		return [];
	}

}
