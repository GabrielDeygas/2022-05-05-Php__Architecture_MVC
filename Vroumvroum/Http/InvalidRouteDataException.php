<?php

namespace Vroumvroum\Http;

use \Exception;
use \Throwable;

final class InvalidRouteDataException extends Exception
{
	private array $route_action;
	public function getRouteAction(): array { return $this->route_action; }

	public function __construct( array $route_action, Throwable $previous = null )
	{
		parent::__construct( 'Invalid route data', 400, $previous );

		$this->route_action = $route_action;
	}
}