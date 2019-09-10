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

    public function __construct($connection = null)
    {
        if ($connection) {
            $this->setConnection($connection);
        }
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
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
        array_unshift($ids, self::REDIS_KEY);
        $redis = $this->redis();
        call_user_func_array([$redis, 'lPush'], $ids);
    }

    protected function pop(int $lastId = 0)
    {
        $id = $this->redis()->rpop(self::REDIS_KEY);
        if (!$this->redis()->lLen(self::REDIS_KEY)) {
            $start = ceil($id / $this->limit);
            $this->create($start);
        }
        return $id;
    }
}