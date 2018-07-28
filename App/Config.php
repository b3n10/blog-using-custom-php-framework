<?php

namespace App;

class Config {

	const ENV_VAR			= 'DATABASE_URL';
	const PREFIX			= 'pgsql';

	const DBHOST			= 'localhost';
	const DBPORT			=	5432;
	const DBUSER			=	'blogger01';
	const DBPASS			=	'blogger01';
	const DBNAME			=	'blog';

	const SHOW_ERRORS	=	true;
	const SECRET_KEY	=	'qpHB25v1wj4A5HfDMvcfERQHJ8ICa7Bx';

	public static function getDSN() {
		$db = parse_url(getenv(self::ENV_VAR));

		$dsn = self::PREFIX . ':'
			. 'host=' 		. ($db['host'] ?? self::DBHOST)
			. ';port=' 		. ($db['port'] ?? self::DBPORT)
			. ';user=' 		. ($db['user'] ?? self::DBUSER)
			. ';password='. ($db['pass'] ?? self::DBPASS)
			. ';dbname='	. (($db['path']) ? ltrim($db['path'], '/') : self::DBNAME);

		return $dsn;
	}

}
