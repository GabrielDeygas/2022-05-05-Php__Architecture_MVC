<?php

namespace App\Model;

use Vroumvroum\Model;

class User_infos extends Model
{
    public int      $id;
    public int      $user_id;
    public string   $first_name;
    public string   $last_name;
    public string   $address;
    public int      $phone;
}