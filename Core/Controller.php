<?php

namespace Core;

use \App\Auth;
use \App\Flash;

abstract class Controller {

	public function __call($name, $args) {
		$method = $name . 'Action';

		if (method_exists($this, $method)) {
			if ($this->before() !== false) {
				call_user_func_array([$this, $method], $args);
				$this->after();
			}
		} else {
			throw new \Exception("Method '$method' doesn't exists in " . get_class($this) . "!");
		}

	}

	protected function before() {}

	protected function after() {}

	protected function redirect($url) {
		header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
		exit;
	}

	public function requireLogin() {
		if (!Auth::getUser()) {
			Flash::addMessage('Please login to access page!', Flash::INFO);
			Auth::previousPage();
			$this->redirect('/login');
		}
	}

}
