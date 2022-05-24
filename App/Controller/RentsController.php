<?php

namespace App\Controller;

use App\AppRepoManager;
use Vroumvroum\View;
use App\Model\User;

class RentsController
{

    public function getRentals(): void
    {
        if ($_SESSION['USER']->user_type === User::CLIENT) {
            $view_data = [
                'h1_tag' => 'Vos réservations',
                'rents' => AppRepoManager::getRm()->getRentsRepo()->showAllRentsForCustomer($_SESSION['USER']->id),
            ];
            $view = new View( 'pages/rents' );
            $view->title = 'Vos chambres';
            $view->render( $view_data );
        }
        if (($_SESSION['USER']->user_type === User::ANNOUNCER) ) {
            $view_data = [
                'h1_tag' => 'Réservations sur vos chambres',
                'rents' => AppRepoManager::getRm()->getRentsRepo()->showAllRentsForOwner($_SESSION['USER']->id)
            ];
            $view = new View( 'pages/rents' );
            $view->title = 'Vos chambres';
            $view->render( $view_data );
        }
    }
}