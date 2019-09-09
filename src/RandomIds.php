<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds;

class RandomIds
{
    protected $fileName;
    protected $limit = 1000000;

    public function __construct($path = '')
    {
        $path = $path ?: __DIR__ . '/data';
        $this->fileName = $path . '/random_ids.data';
    }


    public function getId($lastId = 0)
    {
        $id = $this->read($lastId);
        return $id;
    }

    public function create($start = 0)
    {
        $startNum = ($start + 1) * $this->limit;
        $endNum = $startNum + $this->limit - 1;
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

    protected function read($lastId = 0)
    {
        if (!file_exists($this->fileName)) {
            $start = floor($lastId / $this->limit);
            $this->create($start);
        }
        $size = filesize($this->fileName);
        clearstatcache();
        $file = fopen($this->fileName, "a+");
        $offset = $size - strlen($this->limit);
        fseek($file, $offset);
        $id = intval(fgets($file));
        $eof = $offset - 1;
        if ($eof > 0) {
            ftruncate($file, $eof);
            fclose($file);
        } else {
            fclose($file);
            $start = floor($id / $this->limit);
            $this->create($start);
        }
        return $id;
    }
}