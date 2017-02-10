<?php

namespace Bukka\EET\App\Storage;

use PHPUnit_Framework_TestCase as TestCase;

class StorageResultTest extends TestCase
{
    static $testInfo = ['csv' => ['path' => '/tmp/test']];

    public function testGetInfoTop()
    {
        $result = new StorageResult(self::$testInfo);
        $this->assertSame(self::$testInfo, $result->getInfo());
    }

    public function testGetInfoSectionFound()
    {
        $result = new StorageResult(self::$testInfo);
        $this->assertSame(self::$testInfo['csv'], $result->getInfo('csv'));
    }

    public function testGetInfoSectionNotFound()
    {
        $result = new StorageResult(self::$testInfo);
        $this->assertNull($result->getInfo('unknown'));
    }

    public function testGetInfoSubsectionFound()
    {
        $result = new StorageResult(self::$testInfo);
        $this->assertSame('/tmp/test', $result->getInfo('csv', 'path'));
    }

    public function testGetInfoSubsectionNotFound()
    {
        $result = new StorageResult(self::$testInfo);
        $this->assertNull($result->getInfo('csv', 'unknown'));
    }
}