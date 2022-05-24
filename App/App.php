<?php

namespace App;

use App\Controller\DetailController;
use App\Controller\PageController;
use App\Controller\RoomController;
use App\Controller\RentsController;
use App\Controller\UserController;
use App\Controller\FormController;
use MiladRahimi\PhpRouter\Exceptions\InvalidCallableException;
use Vroumvroum\Database\DatabaseConfig;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use Vroumvroum\View;
use MiladRahimi\PhpRouter\Router;
use App\Middleware\VisitorMiddleware;
use App\Middleware\UserMiddleware;
use App\Middleware\AnnouncerMiddleware;

class App implements DatabaseConfig
{
	private const DB_HOST = 'database';
	private const DB_NAME = 'lamp';
	private const DB_USER = 'lamp';
	private const DB_PASS = 'lamp';

	private static ?self $instance = null;
	public static function getApp(): self
	{
		if( is_null( self::$instance )) self::$instance = new self();

		return self::$instance;
	}

	private Router $router;

	private function __construct() {
		$this->router = Router::create();
	}

	public function getHost(): string
	{
		return self::DB_HOST;
	}

	public function getName(): string
	{
		return self::DB_NAME;
	}

	public function getUser(): string
	{
		return self::DB_USER;
	}

	public function getPass(): string
	{
		return self::DB_PASS;
	}

	public function start(): void
	{
        session_start();
		$this->registerRoutes();
		$this->startRouter();
	}

	private function registerRoutes(): void
	{
		$this->router->pattern( 'id', '[1-9]\d*' );

        $this->router->group(['middleware' => [VisitorMiddleware::class]], function(Router $r) {
            $r->get ( '/', [ PageController::class, 'inscription' ] );
            $r->get ( '/connection', [ PageController::class, 'connection' ] );
            $r->post( '/', [ UserController::class, 'create' ] );
            $r->post( '/registered_l', [ UserController::class, 'auth' ] );
            $r->get ( '/mentions-legales', [ PageController::class, 'legalNotice' ] );
        });

        $this->router->group(['middleware' => [UserMiddleware::class]], function(Router $r) {
            $r->get ('/disconnect', [UserController::class, 'disconnect']);
            $r->get ('/chambres', [RoomController::class, 'listRooms']);
            $r->get ('/reservations', [RentsController::class, 'getRentals']);
            $r->get ('/chambre/{id}', [DetailController::class, 'getDetails']);
            $r->post('/chambre/{id}', [RoomController::class, 'rentARoom']);
            $r->get ('/checkFavOnRoom', [DetailController::class, 'getFavOnRoom']);
            $r->get ('/ajout_favoris/{id}', [DetailController::class, 'modifyFav']);
            $r->get ('/mentions-legales', [ PageController::class, 'legalNotice']);
        });

        $this->router->group(['middleware' => [AnnouncerMiddleware::class]], function(Router $r) {
            $r->get ('/creer_chambre', [PageController::class, 'createRoom']);
            $r->post('/creer_chambre', [RoomController::class, 'createARoom']);
        });
	}

	private function startRouter(): void
	{
		try {
			$this->router->dispatch();
		}
		catch( RouteNotFoundException $e ) {
			View::renderError();

		}
		catch( InvalidCallableException $e ) {
			View::renderError( 500 );

		}
	}

	private function __clone() {}
	private function __wakeup() {}
}