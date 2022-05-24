<?php

namespace Vroumvroum;

class View
{
	public const PATH_VIEWS = PATH_ROOT .'views'. DS;
	public const PATH_PARTIALS = self::PATH_VIEWS .'_partials'. DS;

	public const ERROR_LIST = [ 400, 403, 404, 500, 503 ];

	public string $title = 'TITRE PAR DEFAUT';

	private string $name;
	public function getName(): string { return $this->name; }

	private bool $is_complete;

	public function __construct( string $name, $is_complete = false )
	{
		$this->name = $name;
		$this->is_complete = $is_complete;
	}

	public static function renderError( int $code = 404 ): void
	{
		// Si le code n'est pas dans la liste prévue, on le change en 500
		if( !in_array( $code, self::ERROR_LIST ) ) $code = 500;

		http_response_code( $code );
		$is_complete = $code !== 404;

		$view = new self( 'errors/'. $code, $is_complete );

		if( $code === 404 ) $view->title = 'Page inexistante - Mon super site MVC';

		$view->render();
	}

	public function render( array $view_data = [] ): void
	{
		// Les variables déclarées ici sont visibles dans les fichiers require
		$title_tag = $this->title;

		// Crée des variables à partir d'un tableau associatif
		// Le nom des variables sera le même que les clés du tableau
		extract( $view_data );

		// Mise en cache du résultat
		ob_start();

		if( !$this->is_complete ) {
			require_once self::PATH_PARTIALS .'_top.html.php';
		}

		require_once $this->getRequirePath();

		if( !$this->is_complete ) {
			require_once self::PATH_PARTIALS .'_bottom.html.php';
		}

		// Libération du cache
		ob_end_flush();
	}


	private function getRequirePath(): string
	{
		// On reçoit ce nom: categorie/nom
		$arr_name = explode( '/', $this->name );

		$category = $arr_name[0];
		$name = $arr_name[1];
		$name_prefix = $this->is_complete ? '' : '_';

		return self::PATH_VIEWS . $category . DS . $name_prefix . $name . '.html.php';
	}
}