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

    public function getConnection(int $limit = 0): array
    {
        $rs = [
            'path' => $this->path,
        ];
        if ($limit) {
            $rs['limit'] = $limit;
        }
        return $rs;
    }

    /**
     * @requires function testGetId
     */
    public function testAddIds()
    {
        $randomIds = new FileRandomIds($this->getConnection());
        $randomIds->addIds(10);
        $this->assertFileExists($randomIds->fileName);
    }


    public function testGetId()
    {
        $randomIds = new FileRandomIds($this->getConnection());
        $id = $randomIds->getId();
        echo 'testGetId:id=' . $id;
        $this->assertLessThan($id, 0);
    }

    /**
     * if all ids used
     */
    public function testAllIdsUsed()
    {
        $testValue = [11,22];
        $str = implode("\n", $testValue);
        $randomIds = new FileRandomIds($this->getConnection(10));
        //$randomIds->setLimit(100);
        $fileName = $randomIds->__get('fileName');
        if (file_exists($fileName)) unlink($fileName);
        file_put_contents($fileName, $str);
        $id1 = $randomIds->getId();
        $id2 = $randomIds->getId();
        //$id3 = $randomIds->getId();
        $lastId = $randomIds->getId();
        echo "\ntestAllIdUsed:id1=" . $id1;
        echo "\ntestAllIdUsed:id2=" . $id2;
        echo "\ntestAllIdUsed:lastId=" . $lastId;
        $this->assertLessThan($lastId, $testValue[0]);
    }

    public function testSetLimit()
    {
        $randomIds = new FileRandomIds($this->getConnection());
        $limit = 10;
        $randomIds->setLimit($limit);
        unlink($randomIds->__get('fileName'));
        $id = $randomIds->getId();
        echo('id=' . $id);
        $this->assertLessThan($id, 0);
        $this->assertEquals($randomIds->limit, $limit);
    }

    public function testInitLimit()
    {
        $limit = 10;
        $randomIds = new FileRandomIds($this->getConnection($limit));
        $id = $randomIds->getId();
        echo('id=' . $id);
        $this->assertLessThan($id, 0);
        $this->assertEquals($randomIds->limit, $limit);
    }
}