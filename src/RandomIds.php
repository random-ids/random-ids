<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds;
abstract class RandomIds implements RandomIdsInterface
{
    protected $limit = 1000000;

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * This function is the main function,it will pop an ID from the random store
     * @param int $lastId if your random store accidentally lost, library will create a new high-level storage store,all
     * new IDs will much larger than the $lastId
     * @return integer ID
     */
    abstract function getId(int $lastId);

    /**
     * Set the number of ID generated at a time
     * @param int $limit
     * @throws \Exception
     */
    public function setLimit(int $limit)
    {
        if (!$limit || $limit % 10 > 0) {
            throw new \Exception('$limit must be the power of 10');
        }
        $this->limit = $limit;
    }

    /**
     * @param int $lowNumber
     */
    public function addIds(int $lowNumber = 0)
    {
        if ($this->getStoreLength() <= $lowNumber) {
            $lastId = $this->getId() ?: 0;
            $this->create($lastId);
        }
    }


    /**
     * Create a random IDs array and save it
     * @param int $lastId
     * @return mixed
     */
    protected function create(int $lastId = 0)
    {
        //This function took 2.38seconds and 722.00MB memory on my notebook when $this->limit=1000000
        //When $this->limit=100000,it took Time: 270 ms, Memory: 55.75MB
        //Fortunately, you hardly need to perform this method.
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $start = ceil($lastId / $this->limit / 10);
        $startNum = $start ? $start * $this->limit * 10 : $this->limit;
        $endNum = $start ? $startNum + $this->limit * 10 - 1 : $this->limit * 10 - 1;
        $ids = [];
        for ($i = $startNum; $i <= $endNum; $i++) {
            $ids[] = $i;
        }
        shuffle($ids);
        $this->save($ids);
    }

    /**
     * Gets the current data length
     * @return int
     */
    abstract protected function getStoreLength(): int;

    /**
     * Save IDs
     * @param array $ids
     */
    abstract protected function save(array $ids);

    /**
     * pop an ID from the random store
     * @param int $lastId if your random store accidentally lost, library will create a new high-level storage store,all
     * new IDs will much larger than the $lastId
     * @return integer ID
     */
    abstract protected function pop(int $lastId): int;
}