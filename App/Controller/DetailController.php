<?php

namespace App\Controller;

use App\AppRepoManager;
use Vroumvroum\View;

class DetailController
{
    public function getDetails(int $id): void
    {
        $user = $_SESSION['USER'];
        $view_data = [
            'title'         => 'La Chambre',
            'room_by_id'    => AppRepoManager::getRm()->getRoomRepo()->findCompleteByRoom($id),
            'is_faved'      => AppRepoManager::getRm()->getRoomRepo()->getFavOnRoom($user, $id)
        ];

        $view = new View( 'pages/details' );
        $view->title = 'Chambre';
        $view->render( $view_data );
    }

    public function modifyFav( int $id )
    {
        $user = $_SESSION['USER'];
        $_SESSION['FAV_RESULT'] = AppRepoManager::getRm()->getRoomRepo()->modifyFav($user, $id);
        header( 'Location: /chambre/'. $id );
        die();
    }
}