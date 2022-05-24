<?php

namespace Vroumvroum;

abstract class Model
{
	public int $id;

	public function __construct( array $data_row = [] )
	{
		foreach( $data_row as $column => $value ) {
			if(  ! property_exists( $this, $column ) ) continue;

			$this->$column = $value;
		}
	}
}