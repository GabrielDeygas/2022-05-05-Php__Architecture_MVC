<?php

namespace Vroumvroum;

use Vroumvroum\Database\Database;
use Vroumvroum\Database\DatabaseConfig;
use \PDO;

abstract class Repository
{
	protected PDO $pdo;

	abstract protected function getTableName(): string;

	public function __construct( DatabaseConfig $config )
	{
		$this->pdo = Database::getPDO( $config );
	}

	protected function readAll( string $class_name ): array
	{
		$arr_result = [];

		$q = sprintf( 'SELECT * FROM `%s`', $this->getTableName() );

		$sth = $this->pdo->query( $q );

		if( !$sth ) return $arr_result;

		while( $row_data = $sth->fetch() ) $arr_result[] = new $class_name( $row_data );

		return $arr_result;
	}

	protected function readById( string $class_name, int $id ): ?Model
	{
		$q = sprintf( 'SELECT * FROM `%s` WHERE id=:id', $this->getTableName() );

		$sth = $this->pdo->prepare( $q );

		if( !$sth ) return null;

		$sth->execute( [ 'id' => $id ] );

		$row_data = $sth->fetch();

		return !empty( $row_data ) ? new $class_name( $row_data ) : null;
	}
}