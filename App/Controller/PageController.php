<?php

namespace App\Controller;

use App\AppRepoManager;
use Laminas\Diactoros\ServerRequest;
use Vroumvroum\View;

class PageController
{
    public function inscription(ServerRequest $request)
    {
        if (empty($_SESSION['USER'])) {
            $view = new View('pages/home');
            $view->title = 'Inscription';

            $view_data = [
                'error' => $_SESSION['FORM_RESULT'] ?? ''
            ];

            unset($_SESSION['FORM_RESULT']);

            $view->render($view_data);
        } else {
            View::renderError(403);
            die();
        }
    }

    public function connection(): void
    {
        if (empty($_SESSION['USER'])) {
            $view = new View( 'pages/connection' );
            $view->title = 'Connection';

            $view->render();
        } else {
            View::renderError(403);
            die();
        }
    }

    public function createRoom(): void
    {
        $view_data = [
            'user' => $_SESSION['USER']->nickname
        ];
        $view = new View( 'pages/create-room' );
        $view->title = 'Créer votre chambre';

        $view->render($view_data);
    }

	public function legalNotice(): void
	{
		$view = new View( 'pages/legal-notice' );
		$view->title = 'Mentions illégalement illégales';

		$view->render();
	}
}