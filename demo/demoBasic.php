<?php
require_once('../vendor/autoload.php');
$randomIds = new RandomIds\RandomIds();
echo $randomIds->getId();