<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds\Tests;

use PHPUnit\Framework\TestCase;
use RandomIds\FileRandomIds;
use RandomIds\RedisRandomIds;

class RedisRandomIdsTest extends TestCase
{
    public function getConnection()
    {
        return [
            'host' => '127.0.0.1',
            'port' => '6379',
            'password' => 'K8lYF7k0GJJy',
            'dbindex' => 1
        ];
    }

    /**
     * @requires function testGetId
     */
    public function testCreate()
    {
        $randomIds = new RedisRandomIds($this->getConnection());
        $randomIds->redis()->lPop(RedisRandomIds::REDIS_KEY);
        $len = $randomIds->redis()->lLen(RedisRandomIds::REDIS_KEY);
        $randomIds->create();
        $newLen = $randomIds->redis()->lLen(RedisRandomIds::REDIS_KEY);
        $this->assertLessThan($newLen, $len);
    }


    public function testGetId()
    {
        $randomIds = new RedisRandomIds($this->getConnection());
        $id = $randomIds->getId();
        $this->assertLessThan($id, 0);
    }

    /**
     * if all ids used
     */
    public function testGetMoreId()
    {
        $testValue = [2134567, 2234567];
        $method = new \ReflectionMethod('RandomIds\RedisRandomIds', 'save');
        $method->setAccessible(true);
        $method->invokeArgs(new RedisRandomIds($this->getConnection()), [$testValue]);
        $randomIds = new RedisRandomIds($this->getConnection());
        $randomIds->getId();
        $lastId = $randomIds->getId();
        $this->assertEquals($lastId, $testValue[0]);
        $newId = $randomIds->getId();
        $limit = ceil($lastId / $randomIds->limit) * $randomIds->limit;
        $this->assertLessThan($newId, $limit);
    }

    public function testSetLimit()
    {
        $randomIds = new RedisRandomIds($this->getConnection());
        $limit = 0;
        $randomIds->setLimit($limit);
        $id = $randomIds->getId();
        echo('id=' . $id);
        $this->assertLessThan($id, 0);
        $this->assertEquals($randomIds->limit, $limit);

    }
}