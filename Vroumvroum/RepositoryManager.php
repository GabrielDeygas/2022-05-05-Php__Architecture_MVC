<?php

namespace Vroumvroum;

// Un trait permet de gérer une portion de code utilisable directement dans plusieurs
// classes sans notion de hiérarchie
// De sorte que self représente ici la classe qui utilise le trait
// Si on était dans un context hirarchique,
// le mot self désignerait la classe dans laquelle il apparaît
// https://www.php.net/manual/fr/language.oop5.traits.php
trait RepositoryManager
{
	private static ?self $rm_instance = null;

	public static function getRm(): self
	{
		if( is_null( self::$rm_instance ) ) self::$rm_instance = new self();

		return self::$rm_instance;
	}

	protected function __construct() {}

	private function __clone() {}
	private function __wakeup() {}
}