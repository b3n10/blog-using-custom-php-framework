<?php

namespace Core;

use \App\Config;

class Error {

	public static function errorHandler($level, $msg, $file, $line) {
		if (error_reporting() !== 0) {
			throw new \ErrorException($msg, 0, $level, $file, $line);
		}
	}

	public static function exceptionHandler($e) {
		$code = 0;

		if ($e->getCode() === 404) $code = 404;
		else $code = 500;

		http_response_code($code);

		$error_message = '<h1>Fatal Error: </h1>';
		$error_message .= '<p>Uncaught exception: "' . get_class($e) . '"</p>';
		$error_message .= '<p>Message: "' . $e->getMessage() . '"</p>';
		$error_message .= '<p>Stack Trace: <pre>' . $e->getTraceAsString() . '</pre></p>';
		$error_message .= '<p>Thrown in "' . $e->getFile() . '" on line "' . $e->getLine() . '"';

		if (Config::SHOW_ERRORS) {
			echo $error_message;
		} else {
			$log_file = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
			ini_set('error_log', $log_file);
			error_log($error_message);

			View::render("Errors/$code.php", [
				'title'	=>	"$code"
			]);
		}

	}
}
