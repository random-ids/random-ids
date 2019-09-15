<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds;

use phpDocumentor\Reflection\Types\Object_;

class RedisRandomIds extends RandomIds
{
    protected $connection;
    protected $redisClient;
    const REDIS_KEY = 'random-ids-store';
    protected $redisKey;

    /**
     * RedisRandomIds constructor.
     * @param null $connection
     */
    public function __construct(?array $connection = null)
    {
        if ($connection) {
            $this->setConnection($connection);
        }
    }

    public function setConnection(array $connection)
    {
        $this->connection = $connection;
        $this->redisKey = isset($connection['key']) && $connection['key']
            ? self::REDIS_KEY . '-' . $connection['key']
            : self::REDIS_KEY;
        if (isset($connection['limit']) && $connection['limit']) {
            $this->setLimit($connection['limit']);
        }
    }

    public function redis()
    {
        if (!$this->redisClient) {
            $redis = new \Redis();
            $redis->connect($this->connection['host'], $this->connection['port']);
            if (isset($this->connection['password']) && $this->connection['password']) {
                $redis->auth($this->connection['password']);
            }
            $redis->select($this->connection['dbindex']);
            $this->redisClient = $redis;
        }
        return $this->redisClient;
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
     * @return int
     */
    public function getStoreLength(): int
    {
        return $this->redis()->lLen($this->redisKey);
    }

    /**
     * @param array $ids
     * @throws \Exception
     */
    protected function save(array $ids)
    {
        $redis = $this->redis();
        $chunkLimit = 100000;
        $chunk = array_chunk($ids, $chunkLimit);
        foreach ($chunk as $childrenIds) {
            array_unshift($childrenIds, $this->redisKey);
            call_user_func_array([$redis, 'lPush'], $childrenIds);
        }
    }

    protected function pop(int $lastId = 0): int
    {
        $id = $this->redis()->rpop($this->redisKey);
        if (!$id) {
            $this->create($lastId);
            $id = $this->redis()->rpop($this->redisKey);
        } elseif (!$this->redis()->lLen($this->redisKey)) {
            $this->create($id);
        }
        return $id;
    }
}