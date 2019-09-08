<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds\Tests;

use RandomIds\RandomIds;
use PHPUnit\Framework\TestCase;

class RandomIdsTest extends TestCase
{
    public $path = './test_data';

    /**
     * @requires function testGetId
     */
    public function testCreate()
    {
        $randomIds = new RandomIds($this->path);
        $randomIds->create();
        $this->assertFileExists($randomIds->fileName);
    }


    public function testGetId()
    {
        $randomIds = new RandomIds($this->path);
        $randomIds->getId();
        clearstatcache();
        $startSize = filesize($randomIds->fileName);
        $id = $randomIds->getId();
        clearstatcache();
        $endSize = filesize($randomIds->fileName);
        $this->assertLessThan($id, 0);
        $this->assertLessThan($startSize, $endSize);
    }

    /**
     * if all ids used
     */
    public function testGetMoreId()
    {
        $testValue = 21234567;
        $method = new \ReflectionMethod('RandomIds\RandomIds', 'save');
        $method->setAccessible(true);
        $method->invokeArgs(new RandomIds($this->path), [$testValue]);
        $randomIds = new RandomIds($this->path);
        $lastId = $randomIds->getId();
        $this->assertEquals($lastId, $testValue);
        $newId = $randomIds->getId();
        $limit = ceil($lastId / $randomIds->limit) * $randomIds->limit;
        $this->assertLessThan($newId, $limit);
    }
}