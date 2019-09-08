<?php
require_once('../vendor/autoload.php');
$demo = new Demo();
echo $demo->useGetId();
echo "\n";
echo $demo->useGetId();
echo "\n";
echo $demo->useGetId();
echo "\n";
class Demo
{
    public function useGetId()
    {
        $randomIds = new RandomIds\RandomIds();
        echo $randomIds->getId();
    }
}