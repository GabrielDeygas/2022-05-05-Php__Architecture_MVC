<?php

namespace Vroumvroum\Http;

use \Exception;
use \Throwable;

final class RouteNotFoundException extends Exception
{
	private string $requested_url;
	public function getRequestedUrl(): string { return $this->requested_url; }

	public function __construct( string $requested_url, Throwable $previous = null )
	{
		parent::__construct( 'Route not found', 404, $previous );
		$this->requested_url = $requested_url;
	}
}