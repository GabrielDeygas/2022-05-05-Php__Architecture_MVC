<?php

namespace App\Controller;

use App\AppRepoManager;
use App\Model\Addresses;
use App\Model\Rents;
use App\Model\Rooms;
use App\Model\User;
use Laminas\Diactoros\ServerRequest;
use Vroumvroum\View;

class RoomController
{

	public function listRooms(): void
	{

            $view_data = [
                'h1_tag' => 'Nos Chambres',
                'rooms_all' => AppRepoManager::getRm()->getRoomRepo()->findAllComplete(),
            ];

            $view = new View( 'pages/locations' );
            $view->title = 'Toutes nos chambres';
            $view->render( $view_data );
	}

    public function createARoom(ServerRequest $request): void
    {

        if(isset($_FILES['path_pics']))
        {
            $dossier = 'assets/img/';
            $fichier = basename($_FILES['path_pics']['name']);
            if(move_uploaded_file($_FILES['path_pics']['tmp_name'], $dossier . $fichier))
            {
                echo 'Upload effectué avec succès !';
            }
            else
            {
                echo 'Echec de l\'upload !';
                return;
            }
        }

        $room_data = $request->getParsedBody();

        $path_pics = $_FILES['path_pics']['name'];
        $user_id = $_SESSION['USER']->id;
        $new_room = new Rooms($room_data);
        $new_address = new Addresses($room_data);
        $new_room->addresses = $new_address;

        $message = AppRepoManager::getRm()
            ->getRoomRepo()->createARoom( $new_room, $room_data['equipments'], $user_id, $path_pics);

        $_SESSION[ 'FORM_RESULT' ] = $message;
        header( 'Location: /creer_chambre' );
    }

    public function rentARoom(ServerRequest $request): void
    {
        $rent_data = $request->getParsedBody();


        $new_rent = new Rents($rent_data);
        $new_rent->user_id = $_SESSION['USER']->id;

        $message = AppRepoManager::getRm()->getRoomRepo()->rentRoom($new_rent);

        $_SESSION[ 'FORM_RESULT' ] = $message;
        header( 'Location: /chambre/' . $new_rent->room_id  );
    }
}