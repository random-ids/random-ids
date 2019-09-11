<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds;

class RedisRandomIds extends RandomIds
{
    protected $connection;
    protected $redisClient;
    const REDIS_KEY = 'random-ids-store';
    protected $redisKey;

    public function __construct($connection = null)
    {
        if ($connection) {
            $this->setConnection($connection);
        }
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
        $this->redisKey = isset($connection['key']) && $connection['key']
            ? self::REDIS_KEY . '-' . $connection['key']
            : self::REDIS_KEY;
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

    protected function pop(int $lastId = 0)
    {
        $id = $this->redis()->rpop($this->redisKey);
        if (!$id) {
            $start = ceil($lastId / $this->limit / 10);
            $this->create($start);
            $id = $this->redis()->rpop($this->redisKey);
        } elseif (!$this->redis()->lLen($this->redisKey)) {
            $start = ceil($id / $this->limit / 10);
            $this->create($start);
        }
        return $id;
    }
}