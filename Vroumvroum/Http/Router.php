<?php

namespace Vroumvroum\Http;

use \Throwable;

class Router
{
	private array $routes = [];

	public function __construct() {	}

	public function registerRoute( string $str_request, array $action ): self
	{
		$route = new Route( $str_request, $action );

		$this->routes[] = $route;

		return $this;
	}

	public function start(): void
	{
		$requested_route = null;
		$requested_url = $_SERVER[ 'REDIRECT_URL' ] ?? '/';
		$requested_method = strtolower( $_SERVER[ 'REQUEST_METHOD' ] );

		foreach( $this->routes as $route ) {
			// Si l'url ne correspond pas, on passe au tour suivant
			if( $route->url !== $requested_url ) continue;

			// On est ici car l'URL matche avec celui de $route
			// Si $route->method n'est pas vide ET qu'elle est différente de $requested_method
			if( !empty( $route->method ) && $route->method !== $requested_method ) continue;

			$requested_route = $route;
			break; // on interrompt la boucle (vu qu'on a trouvé la bonne route)
		}

		// var_dump( $requested_route );

		// Si la route demandée n'existe pas, on envoie une erreur 404
		if( is_null( $requested_route ) ) throw new RouteNotFoundException( $requested_url );

		// Si la route est présente, on vérifie que son contenu soit conforme
		// Dans $action on devrait avoir quelque chose comme ça: [ '\App\Pages', 'index' ]

		$is_valid = true;

		// Si action n'a pas au moins 2 indices (classe et méthode de l'action): non-valide
		if( count( $requested_route->action ) < 2 ) $is_valid = false;

		// Sinon si la classe désignée dans l'action n'existe pas, non-valide
		else if( !class_exists( $requested_route->action[0] ) ) $is_valid = false;

		// Sinon si la méthode désignée de l'action n'existe pas, non-valide
		else if( !method_exists( $requested_route->action[0], $requested_route->action[1] ) ) $is_valid = false;

		// Si la validation précédente n'est pas passée avec succès, Jete une exception personnalisée
		if( !$is_valid ) throw new InvalidRouteDataException( $requested_route->action );

		// Ici on est sûr que l'on peut utiliser la classe désignée...
		$page_class = $requested_route->action[0]; // Nom qualifié ("avec son namespace")
		$page_instance = new $page_class(); // Comme si on tapait: new \App\Pages()

		// TODO: Gérer le passage des arguments à l'action
		try {
			// ... Et tenter d'éxécuter la méthode désignée
			// https://www.php.net/manual/fr/function.call-user-func-array.php
			call_user_func_array( [ $page_instance, $requested_route->action[1] ], [] );
		}
		catch( Throwable $e ) {
			// En cas d'échec (le plus probable à cause d'arguments invalides ou absents pour la méthode)
			// On jette une Exception personnalisée, qui contient l'Exception PHP qui a empêché l'exécution de la méthode
			throw new InvalidRouteDataException( $requested_route->action, $e );
		}
	}
}