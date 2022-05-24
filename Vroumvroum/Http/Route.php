<?php

namespace Vroumvroum\Http;

class Route
{
	public string $url;
	public string $method = '';
	public array $action;

	public function __construct( string $str_request, array $action )
	{
		$arr_route = explode( '|', $str_request );

		if( count( $arr_route ) < 2 ) {
			$this->url = $arr_route[0];
		}
		else {
			$this->method = strtolower( $arr_route[0] );
			$this->url = $arr_route[1];
		}

		$this->action = $action;
	}
}