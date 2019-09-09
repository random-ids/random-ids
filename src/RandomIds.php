<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds;

class RandomIds
{
    protected $fileName;
    protected $limit = 10;

    public function __construct($path = '')
    {
        $path = $path ?: __DIR__ . '/data';
        $this->fileName = $path . '/random_ids.data';
    }


    public function getId($lastId = 0)
    {
    //        if (!file_exists($this->fileName)) {
    //            $start = ceil($lastId / $this->limit);
    //            $this->create($start);
    //        }
        $id = $this->read();
        return $id;
    }

    public function create($start = 0)
    {
        if ($start == 0) {
            $startNum = $this->limit / 10;
            $endNum = $this->limit - 1;
        } else {
            $startNum = $start * $this->limit;
            $endNum = $startNum + $this->limit - 1;
        }
        $ids = [];
        for ($i = $startNum; $i <= $endNum; $i++) {
            $ids[] = $i;
        }
        shuffle($ids);
        $this->save($ids);
    }

    public function setLimit($number)
    {
        if ($number % 10 > 0) {
            throw new \Exception('$number must be the power of 10');
        }
        $this->limit = $number;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    protected function save($ids)
    {
        $fileName = $this->fileName;
        $ids = implode("\n", $ids);
        if (!file_put_contents($fileName, $ids)) {
            throw new \Exception('File can\'t write:' . $fileName);
        }
    }

    protected function read()
    {
        $size = filesize($this->fileName);
        clearstatcache();
        $file = fopen($this->fileName, "a+");
        $id = fgets($file);
        $size = $size - strlen($id);
        if ($size > 0) {
            ftruncate($file, $size);
            fclose($file);
        } else {
            fclose($file);
            $start = ceil($id / $this->limit);
            $this->create($start);
        }
        return intval($id);
    }
}