<?php

namespace App;

class Token {

	protected $token;

	public function __construct($val = null) {
		if ($val) {
			$this->token = $val;
		} else {
			$this->token = hash('sha256', bin2hex(random_bytes(16)));
		}
	}

	public function getValue() {
		return $this->token;
	}

	public function getHash() {
		return hash_hmac('sha256', $this->token, Config::SECRET_KEY);
	}
}
