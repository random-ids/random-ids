<?php
require_once('../vendor/autoload.php');
$connection = getConnection();
$randomIds = new RandomIds\RedisRandomIds($connection);
echo $randomIds->getId();

function getConnection()
{
    return [
        'host' => '127.0.0.1',
        'port' => '6379',
        'password' => 'K8lYF7k0GJJy',
        'dbindex' => 1
    ];
}