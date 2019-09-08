<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds;

class RandomIds
{
    protected $fileName;
    protected $limit = 10000000;

    public function __construct($path)
    {
        $this->fileName = $path . '/random_ids.data';
    }

    public function getId()
    {
        $ids = $this->read();
        $id = is_array($ids) ? array_pop($ids) : $ids;
        if (!empty($ids) && is_array($ids)) {
            $this->save($ids);
        } else {
            $start = ceil($id / $this->limit);
            $this->create($start);
        }
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

    public function __get($name)
    {
        return $this->$name;
    }

    protected function save($ids)
    {
        $fileName = $this->fileName;
        $ids = serialize($ids);
        if (!file_put_contents($fileName, $ids)) {
            throw new \Exception('File can\'t write:' . $fileName);
        }
    }

    protected function read()
    {
        $rs = file_get_contents($this->fileName);
        if (!$rs) {
            $this->create();
            $rs = file_get_contents($this->fileName);
        }
        return unserialize($rs);
    }
}