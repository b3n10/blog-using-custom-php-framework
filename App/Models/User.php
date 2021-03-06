<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;

class User extends \Core\Model {

	public $errors = [];

	public function __construct($data = []) {
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
	}

	public function save() {
		if (!empty($this->validate())) {
			return false;
		} else {
			$sql = 'INSERT INTO users (name, email, password_hash, activation_hash)
				VALUES (:name, :email, :password_hash, :activation_hash)';

			$token = new Token();
			$activation_hash = $token->getHash();
			$this->activation_token = $token->getValue();

			$pdo = self::connectDB();
			$stmt = $pdo->prepare($sql);

			$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);

			$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
			$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

			$stmt->bindValue(':activation_hash', $activation_hash, PDO::PARAM_STR);

			return $stmt->execute();
		}
	}

	public function validate() {
		// name
		if ($this->name === '') {
			$this->errors[] = 'Name is required!';
		} else if (strlen($this->name) < 2) {
			$this->errors[] = 'Name must at least have 2 characters!';
		}

		// email
		if (self::emailExists($this->email, $this->id ?? null)) {
			$this->errors[] = 'Email already exists!';
		}
		if (empty($this->email)) {
			$this->errors[] = 'Email is empty!';
		} else if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
			$this->errors[] = 'Email is invalid!';
		}

		// password
		if (isset($this->password)) $this->validatePassword();

		return $this->errors;
	}

	public function validatePassword() {
		if ($this->password !== $this->confirm_password) $this->errors[] = 'Password doesn\'t match confirmation!';
		if (strlen($this->password) < 6) $this->errors[] = 'Password must be at least 6 characters!';
		if (preg_match('/.*[a-z]+.*/', $this->password) === 0) $this->errors[] = 'Password must have at least one lowercase character!';
		if (preg_match('/.*[A-Z]+.*/', $this->password) === 0) $this->errors[] = 'Password must have at least one uppercase character!';
		if (preg_match('/.*\d+.*/', $this->password) === 0) $this->errors[] = 'Password must have at least one numeric character!';
		if (preg_match('/.*[!-\/:-@\[-`{-~].*/', $this->password) === 0) $this->errors[] = 'Password must have at least one symbol!';
	}

	public static function emailExists($email, $ignore_id = null) {
		$user = self::findByEmail($email);

		if ($user) return $user->id != intval($ignore_id);

		return false;
	}

	private static function findByEmail($email) {
		$pdo = self::connectDB();
		$stmt = $pdo->prepare("SELECT * from users WHERE email=:email");
		$stmt->bindValue(":email", $email, PDO::PARAM_STR);

		// fetch data as an object of the class (User in this case)
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

		$stmt->execute();

		return $stmt->fetch();
	}

	public static function authenticate($email, $password) {
		$user = self::findByEmail($email);

		if ($user) {
			if (!$user->is_active) {
				return $user->is_active;
			} else if (password_verify($password, $user->password_hash)) {
				return $user;
			}
		}

		return false;
	}

	public static function findById($id) {
		$pdo = self::connectDB();
		$stmt = $pdo->prepare('SELECT * from users WHERE id=:id');
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);

		// fetch data as an object of the class (User in this case)
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

		$stmt->execute();

		return $stmt->fetch();
	}

	public function rememberLogin() {
		$token = new \App\Token();
		$hashed_token = $token->getHash();
		$this->remember_me = $token->getValue();
		$this->expiry = time() + 60 * 60 * 24 * 30;

		$sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
			VALUES (:tokenhash, :userid, :expiration)';
		$pdo = self::connectDB();
		$stmt = $pdo->prepare($sql);

		$stmt->bindValue(':tokenhash', $hashed_token, PDO::PARAM_STR);
		$stmt->bindValue(':userid', $this->id, PDO::PARAM_INT);
		$stmt->bindValue(':expiration', date('Y-m-d H:i:s', $this->expiry), PDO::PARAM_STR);

		return $stmt->execute();
	}

	public static function sendPasswordReset($email) {
		$user = self::findByEmail($email);

		if ($user) {
			if ($user->startPasswordReset()) {
				$user->sendPasswordResetEmail();
				return true;
			}
		}

		return false;
	}

	protected function startPasswordReset() {

		$token = new Token();
		$token_hash = $token->getHash();
		$this->password_hash_token = $token->getValue();

		$token_expiry = time() + 60 * 60 * 2; // 2 hours

		$sql = 'UPDATE users
			SET password_reset_hash=:prh, password_reset_expiry=:prx
			WHERE id=:id';
		$pdo = self::connectDB();
		$stmt = $pdo->prepare($sql);

		$stmt->bindValue(':prh', $token_hash, PDO::PARAM_STR);
		$stmt->bindValue(':prx', date('Y-m-d H:i:s', $token_expiry), PDO::PARAM_INT);
		$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

		return $stmt->execute();
	}

	protected function sendPasswordResetEmail() {

		$url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_hash_token;

		$text = "Please click on the following link to reset your password: $url. This request will expire in 2 hours.";
		$html = "Please click on this <a href='$url'>link</a> to reset your password. This request will expire in 2 hours.";

		Mail::send($this->email, 'Password Reset', $text, $html);

	}

	public static function findByToken($token) {
		$token = new Token($token);
		$token_hash = $token->getHash();

		$sql = 'SELECT * FROM users
			WHERE password_reset_hash=:token_hash';

		$pdo = self::connectDB();
		$stmt = $pdo->prepare($sql);

		$stmt->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);

		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

		$stmt->execute();

		$user =  $stmt->fetch();

		if ($user) {
			// if current time has not password password_reset_expiry
			if (date('Y-m-d H:i:s', time()) < $user->password_reset_expiry) {
				return $user;
			}
		}

		return false;
	}

	public static function findByActivationToken($token) {
		$token = new Token($token);
		$activation_hash = $token->getHash();

		$sql = 'SELECT * FROM users
			WHERE activation_hash=:activation_hash';

		$pdo = self::connectDB();
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':activation_hash', $activation_hash, PDO::PARAM_STR);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();

		if ($user = $stmt->fetch()) {
			$sql = 'UPDATE users
				SET activation_hash=NULL,
						is_active=1
				WHERE id=:id';

			$pdo = self::connectDB();
			$stmt = $pdo->prepare($sql);
			$stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
			$stmt->execute();

			return true;
		}

		return false;
	}

	public function resetPassword($password) {
		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		$sql = 'UPDATE users
			SET password_hash = :password_hash,
					password_reset_hash = NULL,
					password_reset_expiry = NULL
			WHERE id = :id';

		$pdo = self::connectDB();
		$stmt = $pdo->prepare($sql);

		$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
		$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

		return $stmt->execute();
	}

	public function sendActivationEmail() {

		$url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;

		$text = "Please click on the following link to activate your account: $url.";
		$html = "Please click on the following <a href='$url'>link</a> to activate your account.";

		Mail::send($this->email, 'Account Activation', $text, $html);

	}

	public function updateProfile($data) {

		$this->name = $data['name'];
		$this->email = $data['email'];
		if ($data['password'] !== '') {
			$this->password = $data['password'];
			$this->confirm_password = $data['confirm_password'];
		}

		if (empty($this->validate())) {

			$sql = 'UPDATE users SET
				name=:name,
				email=:email';

			if (isset($this->password)) $sql .= ', password_hash=:password_hash';

			$sql .= ' WHERE id=:id';

			$pdo = self::connectDB();
			$stmt = $pdo->prepare($sql);

			$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
			if (isset($this->password)) $stmt->bindValue(':password_hash', password_hash($this->password, PASSWORD_DEFAULT), PDO::PARAM_STR);
			$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

			return $stmt->execute();
		}

		return false;
	}

}
