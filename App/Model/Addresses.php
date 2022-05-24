<?php

namespace App\Model;

use Vroumvroum\Model;

class Addresses extends Model
{
    public int      $id;
    public string   $country;
    public string   $city;
    public string   $address;
}