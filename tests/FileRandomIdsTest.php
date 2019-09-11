<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds\Tests;

use RandomIds\FileRandomIds;
use PHPUnit\Framework\TestCase;

class FileRandomIdsTest extends TestCase
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
        $testValue = [2134567, 2234567];
        $method = new \ReflectionMethod('RandomIds\FileRandomIds', 'save');
        $method->setAccessible(true);
        $method->invokeArgs(new FileRandomIds($this->path), [$testValue]);
        $randomIds = new FileRandomIds($this->path);
        $randomIds->getId();
        $lastId = $randomIds->getId();
        $this->assertEquals($lastId, $testValue[0]);
        $newId = $randomIds->getId();
        $limit = ceil($lastId / $randomIds->limit) * $randomIds->limit;
        $this->assertLessThan($newId, $limit);
    }

    public function testSetLimit()
    {
        $randomIds = new FileRandomIds($this->path);
        $limit = 10;
        $randomIds->setLimit($limit);
        $id = $randomIds->getId();
        echo('id=' . $id);
        $this->assertLessThan($id, 0);
        $this->assertEquals($randomIds->limit, $limit);

    }
}