<?php

namespace App\Models;

use \App\Token;
use \App\Models\User;
use PDO;

class RememberedLogin extends \Core\Model {

	public static function findByToken($token) {
		$token = new Token($token);
		$hash_token = $token->getHash();

		$sql = 'SELECT * FROM remembered_logins
			WHERE token_hash=:tokenhash';

		$pdo = self::connectDB();
		$stmt = $pdo->prepare($sql);

		$stmt->bindValue(':tokenhash', $hash_token, PDO::PARAM_STR);

		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

		if ($stmt->execute()) {
			return $stmt->fetch();
		}
	}

	public function getUser() {
		return User::findById($this->user_id);
	}

	public function hasExpired() {
		return strtotime($this->expires_at) < time();
	}

	public function delete() {
		$sql = 'DELETE FROM remembered_logins
			WHERE token_hash=:tokenhash';

		$pdo = self::connectDB();
		$stmt = $pdo->prepare($sql);

		$stmt->bindValue(':tokenhash', $this->token_hash, PDO::PARAM_STR);

		$stmt->execute();
	}

}
