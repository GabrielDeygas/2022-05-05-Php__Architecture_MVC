<?php

namespace VroumVroum\Database;

use \PDO;

class Database
{
	private const PDO_OPTIONS = [
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	];

	private static ?PDO $pdoInstance = null;

	public static function getPDO( DatabaseConfig $config ): PDO
	{
		if( is_null( self::$pdoInstance ) ) {
			$dsn = sprintf( 'mysql:dbname=%s;host=%s', $config->getName(), $config->getHost() );

			self::$pdoInstance = new PDO( $dsn, $config->getUser(), $config->getPass(), self::PDO_OPTIONS );
		}

		return self::$pdoInstance;
	}

	private function __construct() {}
	private function __clone() {}
	private function __wakeup() {}
}