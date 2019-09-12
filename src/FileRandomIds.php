<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds;

class FileRandomIds extends RandomIds
{
    protected $fileName;

    public function __construct(string $path = '', int $limit = 0)
    {
        $path = $path ?: __DIR__ . '/data';
        $this->fileName = $path . '/random_ids.data';
        if ($limit) {
            $this->setLimit($limit);
        }
    }


    /**
     * @param int $lastId
     * @return int
     */
    public function getId(int $lastId = 0)
    {
        return $this->pop($lastId);
    }

    /**
     * @param array $ids
     * @throws \Exception
     */
    protected function save(array $ids)
    {
        $fileName = $this->fileName;
        $ids = implode("\n", $ids);
        if (!file_put_contents($fileName, $ids)) {
            throw new \Exception('File can\'t write:' . $fileName);
        }
    }

    /**
     * @param int $lastId
     * @return int
     */
    protected function pop(int $lastId = 0)
    {
        if (!file_exists($this->fileName)) {
            $start = ceil($lastId / $this->limit / 10);
            $this->create($start);
        }
        $size = filesize($this->fileName);
        clearstatcache();
        $file = fopen($this->fileName, "a+");
        $tempId = fgets($file);
        $offset = $size > strlen($tempId) ? $size - strlen($tempId) + 1 : 0;
        fseek($file, $offset);
        $id = intval(fgets($file));
        $eof = $offset - 1;
        if ($eof > 0) {
            ftruncate($file, $eof);
            fclose($file);
        } else {
            fclose($file);
            $start = ceil($id / $this->limit / 10);
            $this->create($start);
        }
        return $id;
    }
}