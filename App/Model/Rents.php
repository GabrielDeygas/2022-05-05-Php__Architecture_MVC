<?php

namespace App\Model;

use Vroumvroum\Model;

class Rents extends Model
{
    public int    $id;
    public string $date_start;
    public string $date_end;
    public int    $user_id;
    public int    $room_id;

    public ?Rooms $room = null;


}