<?php

namespace Core;

use \App\Config;
use PDO;

class Model {

	public static function connectDB() {
		static $db = null;

		if (!$db) {
			try {
				$db = new PDO(Config::getDSN());
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				throw new \Exception($e->getMessage());
			}
		}

		return $db;
	}

}
