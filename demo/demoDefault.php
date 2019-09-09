<?php

use RandomIds\RandomIds;

require_once('../vendor/autoload.php');
//Specify the folder where random data is stored. If not specified, the data will be stored in the vendor....
$path = './demo_data';
$randomIds = new RandomIds\RandomIds($path);
//once
$lastId = $randomIds->getId();
$cache = new Cache();
$cache->set('lastId', $lastId);
//next time
$lastId = $cache->get('lastId');
//Enter the last acquired id value and automatically generate the next order of magnitude random table if the data file is lost.
$id = $randomIds->getId($lastId);
echo $id;


class Cache
{
    public $cacheFile;

    public function __construct($path)
    {
        $this->cacheFile = $path . '/cache.data';
    }

    public function get($key)
    {
        $cache = $this->load();
        return $cache[$key] ?? NULL;
    }

    public function set($key, $value)
    {
        $array = [$key => $value];
        $this->save($array);
    }

    protected function load()
    {
        $cache = [];
        if (fileExists($this->cacheFile)) {
            $content = file_get_contents($this->cacheFile);
            if ($content) {
                $cache = unserialize($content);
            }
        }
        return $cache;
    }

    protected function save($array)
    {
        if (!is_array($array)) {
            throw new \Exception('Cache content must be an array!');
        }
        $old = $this->load();
        $content = serialize(array_merge($old, $array));
        file_put_contents($this->cacheFile, $content);
    }
}