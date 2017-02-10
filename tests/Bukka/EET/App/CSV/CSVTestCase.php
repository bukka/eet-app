<?php

namespace Bukka\EET\App\CSV;

use PHPUnit_Framework_TestCase as TestCase;

class CSVTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();

        $this->baseDir = __DIR__ . '/test/';
        if (!is_dir($this->baseDir)) {
            mkdir($this->baseDir);
        }

        $this->filePath = $this->baseDir . $this->fileName;
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        if (is_file($this->filePath)) {
            unlink($this->filePath);
        }
        if (is_dir($this->baseDir)) {
            rmdir($this->baseDir);
        }

        parent::tearDown();
    }
}