<?php

namespace App\Model;

use Vroumvroum\Model;

class Rooms extends Model
{
    const PRIVATE_HOUSE = 1;
    const PRIVATE_ROOM  = 2;
    const SHARED_ROOM   = 3;


	public int    $id;
	public string $path_pics;
	public int    $room_type;
	public int    $surface;
	public string $description;
	public int    $nb_sleep;
	public float  $price;
	public int    $owner_id;
	public int    $address_id;
	public bool   $is_published;
    public string $dispo_from;
    public string $dispo_to;


    public ?Addresses $addresses = null;
    public array $equipments = [];
    public array $rents = [];
}