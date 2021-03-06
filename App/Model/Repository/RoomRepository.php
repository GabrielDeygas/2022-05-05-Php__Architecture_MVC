<?php

namespace App\Model\Repository;

use App\AppRepoManager;
use App\Model\Rooms;
use App\Model\Addresses;
use App\Model\Rents;
use App\Model\User;
use Vroumvroum\Repository;
use \DateInterval;
use \DatePeriod;
use \DateTime;

class RoomRepository extends Repository
{
	protected function getTableName(): string { return 'rooms'; }

    public function findAllComplete() : array
    {

        $arr_result = [];

        $q = 'SELECT rooms.*, addresses.city, addresses.country, addresses.address FROM rooms
                JOIN addresses on rooms.address_id = addresses.id';

        $sth = $this->pdo->query( $q );

        if( !$sth ) return $arr_result;

        while( $row_data = $sth->fetch() ) {
            $room = new Rooms($row_data);
            $address_data = [
                'id' => $room->address_id,
                'country' => $row_data['country'],
                'city' => $row_data['city'],
                'address' => $row_data['address'],
            ];

            $room->addresses = new Addresses($address_data);
            $room->equipments = AppRepoManager::getRm()->getEquipRepo()->EquipForRoomId( $room->id );

            $arr_result[] = $room;
        }
        return $arr_result;
    }

    public function findCompleteByRoom( int $room_id ): ?Rooms
    {
        $arr_result = [];

        $q = 'SELECT rooms.*, addresses.city, addresses.country, addresses.address FROM rooms
                JOIN addresses on rooms.address_id = addresses.id
                WHERE rooms.id=:room_id';

        $sth = $this->pdo->prepare( $q );

        if( !$sth ) return null;

        $sth->execute( [ 'room_id' => $room_id ] );
        $row_data = $sth->fetch();
            $room = new Rooms($row_data);
            $address_data = [
                'id' => $room->address_id,
                'country' => $row_data['country'],
                'city' => $row_data['city'],
                'address' => $row_data['address'],
            ];

            $room->addresses = new Addresses($address_data);
            $room->equipments = AppRepoManager::getRm()->getEquipRepo()->EquipForRoomId( $room->id );

            return $room;
    }

    public function modifyFav(User $user, int $room_id): string
    {
        $q_ask = 'SELECT count(*) as count FROM favs
                WHERE room_id=:room_id && favs.user_id=:user_id';

        $sth_ask = $this->pdo->prepare( $q_ask );

        if( !$sth_ask ) return 'La requ??te a ??chou??e 1';


        var_dump($room_id);
        var_dump($user->id);

        $sth_ask->execute( [
            'room_id' => $room_id,
            'user_id' => $user->id
        ] );

        $row_data = $sth_ask->fetch();

        if (empty($row_data['count'])) {
            $q_insert_fav = 'INSERT INTO favs (`user_id`, `room_id`)
                             VALUES (:user_id, :room_id)';

            $sth_insert_fav = $this->pdo->prepare( $q_insert_fav );

            if( !$sth_insert_fav ) return 'La requ??te a ??chou??e 2';

            var_dump($user->id);

            $success_insert_fav = $sth_insert_fav->execute( [
                'user_id'     => $user->id,
                'room_id'     => $room_id
            ] );

            var_dump($sth_insert_fav->errorInfo());

            if (! $success_insert_fav) return 'La requ??te a ??chou??e, nous n\'arrivons pas a enregistrer ce favori';

        } else {
            $q_drop_fav = 'DELETE FROM favs
                            WHERE user_id=:user_id && favs.room_id=:room_id';

            $sth_drop_fav = $this->pdo->prepare( $q_drop_fav );

            if( !$sth_drop_fav ) return 'La requ??te a ??chou??e 3';

            $success_drop_fav = $sth_drop_fav->execute( [
                'user_id'     => $user->id,
                'room_id'     => $room_id
            ] );

            if (! $success_drop_fav) return 'La requ??te a ??chou??e, nous n\'arrivons pas a supprimer ce favori';
        }
        return 'Action r??ussie';
    }

        public function getFavOnRoom(User $user, int $room_id): bool
    {
        $q = 'SELECT count(*) as count FROM favs
                WHERE room_id=:room_id && favs.user_id=:user_id';

        $sth = $this->pdo->prepare( $q );

        if( !$sth ) return 'La requ??te a ??chou??e 1';

        $sth->execute( [
            'room_id' => $room_id,
            'user_id' => $user->id
        ] );

        $row_data = $sth->fetch();

        $result = $row_data['count'];

        if ($result) return true;
        return false;
    }

    public function createARoom(Rooms $room, array $equipments_ids, int $user_id, string $path_pics): string
    {

        $q1 =  'INSERT INTO addresses (`country`, `city`, `address`)
                        VALUES (:country, :city, :address)';

        $sth1 = $this->pdo->prepare( $q1 );

        if( !$sth1 ) return 'La requ??te a ??chou??e, vous avez mal rempli les donn??es de l\'adresse';

        $success1 = $sth1->execute( [
            'country'   => $room->addresses->country,
            'city'      => $room->addresses->city,
            'address'   => $room->addresses->address
        ] );

        $q2 =  'INSERT INTO rooms (`path_pics`, `room_type`, `surface`, `description`, `nb_sleep`, `price`, `dispo_from`,
                   `dispo_to`, `owner_id`, `address_id`)
                        VALUES (:path_pics, :room_type, :surface, :description, :nb_sleep, :price, :dispo_from, 
                                :dispo_to, :owner_id, :address_id)';

        $sth2 = $this->pdo->prepare( $q2 );

        if( !$sth2 ) return 'La requ??te a ??chou??e, vous avez mal rempli les donn??es de la chambre';


        $success2 = $sth2->execute( [
            'path_pics'     => $path_pics,
            'room_type'     => $room->room_type,
            'surface'       => $room->surface,
            'description'   => $room->description,
            'nb_sleep'      => $room->nb_sleep,
            'price'         => $room->price,
            'dispo_from'    => $room->dispo_from,
            'dispo_to'      => $room->dispo_to,
            'owner_id'      => $user_id,
            'address_id'    => $this->pdo->lastInsertId()
        ] );


        $q3 =  'INSERT INTO link_equipments (`room_id`, `equipment_id`)
                        VALUES (:room_id, :equipment_id)';

        $sth = $this->pdo->prepare( $q3 );

        if( !$sth ) return 'La requ??te a ??chou??e, vous avez mal rempli les donn??es de l\'adresse';

        $room_id = $this->pdo->lastInsertId();

        foreach ($equipments_ids as $id){
            $success3 = $sth->execute( [
                'room_id'       => $room_id,
                'equipment_id'  => $id
            ] );
        }

        if (!$success1) return 'La requ??te a ??chou??e, la base de donn??es n\'arrive pas a enregistrer votre chambre';
        if (!$success2) return 'La requ??te a ??chou??e, la base de donn??es n\'arrive pas a enregistrer votre chambre';

        return 'Nous avons bien ajout?? votre chambre';
    }

    public function rentRoom(Rents $rent): string
    {
        $q = 'SELECT rooms.dispo_from, rooms.dispo_to FROM rooms
                WHERE rooms.id=:id';

        $sth = $this->pdo->prepare( $q );

        if( !$sth ) return 'La requ??te a ??chou??e pliplaplou';

        $sth->execute( [ 'id' => $rent->room_id ] );

        $room = new Rooms( $sth->fetch() );

        $dispo_from_clean   = intval(str_replace("-", "", "$room->dispo_from"));
        $dispo_to_clean     = intval(str_replace("-", "", "$room->dispo_to"));

        $date_start_rent_clean  = intval(str_replace("-", "", "$rent->date_start"));
        $date_end_rent_clean    = intval(str_replace("-", "", "$rent->date_end"));

        if ( ($date_start_rent_clean < $dispo_from_clean || $date_start_rent_clean > $dispo_to_clean)
            || ($date_end_rent_clean < $dispo_from_clean || $date_end_rent_clean > $dispo_to_clean) ) {
            return  "La chambre n'est pas r??servable sur ces date";
        }

        $begin = new DateTime( $rent->date_start );
        $end = new DateTime( $rent->date_end );
        $end = $end->modify( '+1 day' );

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);

        $rented_dates = [];
        foreach($daterange as $date){
            $rented_dates[] = $date->format("Y-m-d");
        }

        $q2 = 'SELECT date_rented from occupation
               JOIN rents r on r.id = occupation.rent_id
               WHERE r.room_id=:id';

        $sth2 = $this->pdo->prepare( $q2 );

        if( !$sth2 ) return 'La requ??te a ??chou??e';

        $room_id = $rent->room_id;
        $success = $sth2->execute( [
            'id'    => $room_id
        ] );
        $sth2->debugDumpParams();
        $list_existent_rents = [];
        while($row_data = $sth2->fetch()) {
            $list_existent_rents[] = $row_data['date_rented'];
        }

        var_dump($list_existent_rents);

        $arr_matches = array_intersect($rented_dates, $list_existent_rents);
        if(!empty($arr_matches)) return 'Ces cr??neaux ne sont pas disponibles';

        $q3 =  'INSERT INTO rents (`date_start`, `date_end`, `user_id`, `room_id` )
                        VALUES (:date_start, :date_end, :user_id, :room_id)';

        $sth3 = $this->pdo->prepare( $q3 );

        if( !$sth3 ) return 'La requ??te a ??chou??e';

        $success = $sth3->execute( [
            'date_start'    => $rent->date_start,
            'date_end'      => $rent->date_end,
            'user_id'       => $rent->user_id,
            'room_id'       => $rent->room_id
        ] );

        if (!$success) return 'La requ??te a ??chou??e, la base de donn??es n\'arrive pas a enregistrer votre r??servation3';

        $q4 = 'INSERT INTO occupation (`date_rented`, `rent_id`)
                       VALUES (:date_rented, :rent_id)';

        $sth4 = $this->pdo->prepare( $q4 );

        if( !$sth4 ) return 'La requ??te a ??chou??e';

        var_dump($rented_dates);

        $rent_id = $this->pdo->lastInsertId();
        foreach ($rented_dates as $date){
            $success = $sth4->execute( [
                'date_rented'   => $date,
                'rent_id'       => $rent_id
            ] );
        }

        if (!$success) return 'La requ??te a ??chou??e, la base de donn??es n\'arrive pas a enregistrer votre r??servation4';

        header( 'Location: /chambre?id=' . $room_id);
        return 'Nous avons bien valid?? votre r??servation';
    }
}