<?php
require_once('../vendor/autoload.php');
//Specify the folder where random data is stored. If not specified, the data will be stored in the vendor.
$path = './demo_data';
$limit=100;//It will create a random IDs store include 100-999,When all IDs used,will auto create 1000-1999,2000-2999......
$randomIds = new RandomIds\FileRandomIds($path,100);
//once
$lastId = $randomIds->getId();
//next time
//Enter the last acquired id value and automatically generate the next order of magnitude random table if the data file is lost.
$id = $randomIds->getId($lastId);
echo $id;