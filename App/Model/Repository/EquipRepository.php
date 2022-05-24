<?php

namespace App\Model\Repository;

use App\Model\Equipments;
use Vroumvroum\Repository;

class EquipRepository extends Repository
{

        protected function getTableName(): string { return 'equipments'; }

    public function EquipForRoomId( int $id = 0 ): array
    {
        if ($id === 0) return [];

        $link_equipments = [];

        $q = sprintf( 'SELECT %1$s.* FROM `%1$s` 
                                JOIN link_equipments le on `%1$s`.id = le.equipment_id
                                WHERE room_id=:id', $this->getTableName() );

        $sth = $this->pdo->prepare( $q );

        if( !$sth ) return [];

        $sth->execute( [ 'id' => $id ] );


        while($row_data = $sth->fetch()){
            $link_equipments[] = new Equipments($row_data);
        }

        return $link_equipments;
    }
}
