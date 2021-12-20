<?php

require_once  __DIR__ . '/../bootstrap.php';

use app\User;
use app\Connection;
use app\DB;

$connection = new Connection('mysql:host=localhost;dbname=wisebits', 'user', 'pass');
$db = new DB($connection);


//$user5th = (new User($db))->getOne(5);

//$userNew = (new User())->create([]);

//$userUpdate = (new User())->update();

//$userDelete = (new User())->deleted();