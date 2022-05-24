<?php

use App\App;

const DS = DIRECTORY_SEPARATOR;
const PATH_ROOT =  __DIR__ . DS;

require_once PATH_ROOT . 'vendor/autoload.php';

App::getApp()->start();