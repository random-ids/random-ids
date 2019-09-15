<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds\Tests;

use PHPUnit\Framework\TestCase;
use RandomIds\RedisRandomIds;

class RedisRandomIdsTest extends TestCase
{
    public function getConnection()
    {
        return [
            'host' => '127.0.0.1',
            'port' => '6379',
            'password' => 'K8lYF7k0GJJy',
            'dbindex' => 1,
            'key' => 'test'
        ];
    }

    public function testAddIds()
    {
        $randomIds = new RedisRandomIds($this->getConnection());
        $randomIds->redis()->ltrim($randomIds->__get('redisKey'), 1, 2);
        $oldLength = $randomIds->getStoreLength();
        $randomIds->addIds(10);
        $newLength = $randomIds->getStoreLength();
        $this->assertLessThan($newLength, $oldLength);
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
        $randomIds = new RedisRandomIds($this->getConnection());
        $randomIds->redis()->ltrim($randomIds->__get('redisKey'), 1, 2);
        $length = $randomIds->getStoreLength();
        echo "\ntestGetMoreId:length=" . $length;
        $randomIds->getId();
        $randomIds->getId();
        $lastId = $randomIds->getId();
        echo "\ntestGetMoreId:lastId=" . $lastId;
        $this->assertLessThan($randomIds->getStoreLength(), 10);
    }

    public function testSetLimit()
    {
        $randomIds = new RedisRandomIds($this->getConnection());
        $limit = 100;
        $randomIds->setLimit($limit);
        $id = $randomIds->getId();
        echo("\ntestSetLimit:id=" . $id);
        $this->assertLessThan($id, 0);
        $this->assertEquals($randomIds->limit, $limit);
    }

    public function testInitLimit()
    {
        $conn = $this->getConnection();
        $conn['limit'] = 100;
        $randomIds = new RedisRandomIds($conn);
        $id = $randomIds->getId();
        echo("\ntestInitLimit:id=" . $id);
        $this->assertLessThan($id, 0);
        $this->assertEquals($randomIds->limit, $conn['limit']);
    }
}