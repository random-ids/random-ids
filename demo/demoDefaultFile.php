<?php
require_once('../vendor/autoload.php');
//Specify the folder where random data is stored. If not specified, the data will be stored in the vendor....
$path = './demo_data';
$randomIds = new RandomIds\FileRandomIds($path);
//once
$lastId = $randomIds->getId();
//next time
//Enter the last acquired id value and automatically generate the next order of magnitude random table if the data file is lost.
$id = $randomIds->getId($lastId);
echo $id;