<?php
require_once('../vendor/autoload.php');
//Specify the folder where random data is stored. If not specified, the data will be stored in the vendor....
$connection = getConnection();
$randomIds = new RandomIds\RedisRandomIds($connection);
//once
$lastId = $randomIds->getId();
//next time
//Enter the last acquired id value and automatically generate the next order of magnitude random table if the data file is lost.
$id = $randomIds->getId($lastId);
echo $id;

function getConnection()
{
    return [
        'host' => '127.0.0.1',
        'port' => '6379',
        'password' => 'K8lYF7k0GJJy',
        'dbindex' => 2,
        'key'=>'order-number' //If you want to generate multiple ID in the same project,you can custom the redis key
    ];
}