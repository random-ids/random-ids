<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds\Tests;

use RandomIds\FileRandomIds;
use PHPUnit\Framework\TestCase;

class RandomIdsTest extends TestCase
{
    public $path = './test_data';

    /**
     * @requires function testGetId
     */
    public function testCreate()
    {
        $randomIds = new FileRandomIds($this->path);
        $randomIds->create();
        $this->assertFileExists($randomIds->fileName);
    }


    public function testGetId()
    {
        $randomIds = new FileRandomIds($this->path);
        $id = $randomIds->getId();
        $this->assertLessThan($id, 0);
    }

    /**
     * if all ids used
     */
    public function testGetMoreId()
    {
        $testValue = 21234567;
        $method = new \ReflectionMethod('RandomIds\FileRandomIds', 'save');
        $method->setAccessible(true);
        $method->invokeArgs(new FileRandomIds($this->path), [$testValue]);
        $randomIds = new FileRandomIds($this->path);
        $lastId = $randomIds->getId();
        $this->assertEquals($lastId, $testValue);
        $newId = $randomIds->getId();
        $limit = ceil($lastId / $randomIds->limit) * $randomIds->limit;
        $this->assertLessThan($newId, $limit);
    }

    public function testSetLimit()
    {
        $randomIds = new FileRandomIds($this->path);
        $limit = 1000;
        $randomIds->setLimit($limit);
        $this->assertEquals($randomIds->limit, $limit);
    }
}