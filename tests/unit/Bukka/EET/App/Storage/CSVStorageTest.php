<?php

namespace Bukka\EET\App\Storage;

use Bukka\EET\App\CSV\CSVWriter;
use Bukka\EET\App\Dto\ResponseDto;
use Bukka\EET\App\Transformer\ResponseDtoToArrayTransformer;
use PHPUnit_Framework_TestCase as TestCase;

class CSVStorageTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $csvWriter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $responseTransformer;

    /**
     * @var CSVStorage
     */
    private $storage;


    public function setUp()
    {
        $this->csvWriter = $this->createMock(CSVWriter::class);
        $this->responseTransformer = $this->createMock(ResponseDtoToArrayTransformer::class);
        $this->storage = new CSVStorage($this->csvWriter, $this->responseTransformer);
    }

    public function testOpen()
    {
        $this->csvWriter
            ->expects($this->once())
            ->method('create')
            ->with('path.csv');

        $this->storage->open('path.csv');
    }

    public function testAddBasic()
    {
        $response = $this->createMock(ResponseDto::class);
        $row = ['uuid' => 1];

        $this->responseTransformer
            ->expects($this->once())
            ->method('transform')
            ->with($response)
            ->willReturn($row);

        $this->csvWriter
            ->expects($this->once())
            ->method('insert')
            ->with($row);

        $this->storage->add($response);
    }

    public function testSaveBasic()
    {
        $this->csvWriter
            ->expects($this->once())
            ->method('close');

        $this->csvWriter
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('path.csv');

        $result = $this->storage->save();

        $this->assertInstanceOf(StorageResult::class, $result);
        // this is not exactly a clean unit test as it depends on StorageResult::getInfo,
        // which has to return info correctly but will do for now - factory might be better
        // in the future though
        $this->assertSame(['csv' => ['path' => 'path.csv']], $result->getInfo());

    }
}