<?php

namespace App\Model\Repository;

use App\Model\Addresses;
use App\Model\Rents;
use App\Model\Rooms;
use Vroumvroum\Repository;
use App\AppRepoManager;


class RentsRepository extends Repository
{
    protected function getTableName(): string { return 'rents'; }

    public function showAllRentsForCustomer(int $user_id = 0): array
    {

        if ($user_id === 0) return [];

        $list_rents = [];

        $q = sprintf( 'SELECT 
                                    rt.*,
                                    addresses.country,
                                    addresses.city,
                                    addresses.address,
                                    rooms.path_pics,
                                    rooms.room_type,
                                    rooms.surface,
                                    rooms.description,
                                    rooms.nb_sleep,
                                    rooms.price,
                                    rooms.owner_id,
                                    rooms.address_id,
                                    rooms.is_published
                                FROM `%1$s` as rt
                                JOIN rooms on rt.room_id = rooms.id
                                JOIN addresses on rooms.address_id = addresses.id
                                WHERE rt.user_id = :user_id
                                ORDER BY rt.room_id, rt.date_start', $this->getTableName() );

        $sth = $this->pdo->prepare( $q );

        if( !$sth ) return [];

        $sth->execute( [ 'user_id' => $user_id ] );

        while($row_data = $sth->fetch()){
            $rent = new Rents($row_data);

            $room = new Rooms( $row_data );
            $room->id = $rent->room_id;

            $room->addresses = new Addresses($row_data);
            $room->addresses->id = $rent->room_id;

            $room->equipments = AppRepoManager::getRm()->getEquipRepo()->EquipForRoomId( $room->id );

            $rent->room = $room;


            $list_rents[] = $rent;
        }

        return $list_rents;
    }

    public function showAllRentsForOwner(int $owner_id): array
    {

        if ($owner_id === 0) return [];

        $list_rents = [];


        $q = sprintf( '  SELECT      
                                
                                addresses.country,
                                addresses.city,
                                addresses.address,
                                rooms.path_pics,
                                rooms.room_type,
                                rooms.surface,
                                rooms.description,
                                rooms.nb_sleep,
                                rooms.price,
                                rooms.owner_id,
                                rooms.address_id,
                                rooms.is_published,
                                rooms.dispo_from,
                                rooms.dispo_to,
                                rt.*
                                FROM `%1$s` as rt
                                JOIN rooms on rt.room_id = rooms.id && rooms.owner_id = :owner_id
                                JOIN addresses on rooms.address_id = addresses.id
                                ORDER BY rt.room_id', $this->getTableName() );

        $sth = $this->pdo->prepare( $q );

        if( !$sth ) return [];

        $sth->execute( [ 'owner_id' => $owner_id ] );

        while($row_data = $sth->fetch()){
            $rent = new Rents($row_data);

            $room = new Rooms( $row_data );
            $room->id = $rent->room_id;

            $room->addresses = new Addresses($row_data);
            $room->addresses->id = $rent->room_id;

            $room->equipments = AppRepoManager::getRm()->getEquipRepo()->EquipForRoomId( $room->id );

            $rent->room = $room;


            $list_rents[] = $rent;
        }

        return $list_rents;
    }

    public function showAllRentsByRoom( int $id = 0 ): array
    {
        if ($id === 0) return [];

        $list_rents = [];

        $q = sprintf( 'SELECT `%1$s`.*, rooms.description FROM `%1$s`
                                JOIN rooms on `%1$s`.room_id = rooms.id
                                WHERE rooms.id=:id
                                ORDER BY `%1$s`.date_end', $this->getTableName() );

        $sth = $this->pdo->prepare( $q );

        if( !$sth ) return [];

        $sth->execute( [ 'id' => $id ] );

        while($row_data = $sth->fetch()){
            $list_rents[] = new Rents($row_data);
        }

        return $list_rents;
    }


}