<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds;

class FileRandomIds extends RandomIds
{
    protected $fileName;
    protected $connection;

    public function __construct(array $connection = null)
    {
        if ($connection) {
            $this->setConnection($connection);
        }
    }

    public function setConnection(array $connection)
    {
        $this->connection = $connection;
        $path = isset($connection['path']) ? $connection['path'] : __DIR__ . '/data';
        $this->fileName = $path . '/random_ids.data';
        if (isset($connection['limit']) && $connection['limit']) {
            $this->setLimit($connection['limit']);
        }
    }

    /**
     * @param int $lastId
     * @return int
     */
    public function getId(int $lastId = 0): int
    {
        return $this->pop($lastId);
    }

    /**
     * @return int
     */
    public function getStoreLength(): int
    {
        if (!file_exists($this->fileName)) {
            return 0;
        }
        $size = filesize($this->fileName);
        clearstatcache();
        $file = fopen($this->fileName, "a+");
        $tempId = fgets($file);
        return ceil($size / strlen($tempId));
    }

    /**
     * @param array $ids
     * @throws \Exception
     */
    protected function save(array $ids)
    {
        $fileName = $this->fileName;
        $ids = implode("\n", $ids);
        $ids = file_exists($fileName) ? $ids . "\n" . file_get_contents($fileName) : $ids;
        if (!file_put_contents($fileName, $ids)) {
            throw new \Exception('File can\'t write:' . $fileName);
        }
    }

    /**
     * @param int $lastId
     * @return int
     */
    protected function pop(int $lastId = 0): int
    {
        if (!file_exists($this->fileName)) {
            $this->create($lastId);
        }
        $size = filesize($this->fileName);
        clearstatcache();
        $file = fopen($this->fileName, "a+");
        $tempId = trim(fgets($file));
        $idLen = strlen($tempId);
        $offset = $size == $idLen ? 0 : $size - $idLen;
        fseek($file, $offset);
        $id = intval(trim(fgets($file)));
        $eof = $offset > 1 ? $offset - 1 : 0;
        ftruncate($file, $eof);
        fclose($file);
        if ($eof == 0) {
            $this->create($tempId);
        }
        return $id;
    }
}