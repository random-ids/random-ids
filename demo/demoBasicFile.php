<?php
require_once('../vendor/autoload.php');
$randomIds = new RandomIds\FileRandomIds();
echo $randomIds->getId();