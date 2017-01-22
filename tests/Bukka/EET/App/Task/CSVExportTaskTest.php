<?php

namespace Bukka\EET\App\Task;

use Bukka\EET\App\CSV\CSVReader;
use Bukka\EET\App\Driver\DriverInterface;
use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Dto\ResponseDto;
use Bukka\EET\App\Storage\StorageInterface;
use Bukka\EET\App\Transformer\ReceiptTransformerInterface;
use Bukka\EET\App\Validator\ReceiptValidatorInterface;
use PHPUnit_Framework_TestCase as TestCase;

class CSVExportTaskTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $receiptValidator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $receiptTransformer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $driver;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $storage;


    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $csvReader;

    /**
     * @var CSVExportTask
     */
    private $task;

    public function setUp()
    {
        $this->receiptTransformer = $this->createMock(ReceiptTransformerInterface::class);
        $this->receiptValidator = $this->createMock(ReceiptValidatorInterface::class);
        $this->driver = $this->createMock(DriverInterface::class);
        $this->storage = $this->createMock(StorageInterface::class);
        $this->csvReader = $this->createMock(CSVReader::class);
        $this->task = new CSVExportTask(
            $this->receiptTransformer,
            $this->receiptValidator,
            $this->driver,
            $this->storage
        );
    }

    public function testExportBasic()
    {
        $rows = [
            [
                'uuid_zpravy' => 1,
                'dat_odesl' => "10.1.2017 9:10:01",
                'prvni_zadani' => "ano",
                'overeni' => "ano",
                'dic_popl' => "CZ24222224",
                'id_provoz' => 101,
                'id_pokl' => 3,
                'porad_cis' => 5862,
                'dat_trzby' => "9.1.2017",
                'celk_trzba' => 100,
                'zakl_dan1' => 83,
                'dan1' => 17,
                'rezim' => 0,
            ],
            [
                'uuid_zpravy' => 2,
                'dat_odesl' => "10.1.2017 9:10:02",
                'prvni_zadani' => "ano",
                'overeni' => "ano",
                'dic_popl' => "CZ24222224",
                'id_provoz' => 101,
                'id_pokl' => 3,
                'porad_cis' => 5863,
                'dat_trzby' => "9.1.2017",
                'celk_trzba' => 200,
                'zakl_dan1' => 166,
                'dan1' => 34,
                'rezim' => 0,
            ],
        ];

        $iterator = new \ArrayIterator($rows);

        $this->csvReader
            ->method('fetch')
            ->willReturn($iterator);

        $dtos = [
            $this->createMock(ReceiptDto::class),
            $this->createMock(ReceiptDto::class),
        ];

        $this->receiptTransformer
            ->expects($this->exactly(2))
            ->method('transform')
            ->withConsecutive([$rows[0]], [$rows[1]])
            ->willReturnOnConsecutiveCalls(...$dtos);

        $this->receiptValidator
            ->expects($this->exactly(2))
            ->method('validate')
            ->withConsecutive([$dtos[0]], [$dtos[1]]);

        $responses = [
            $this->createMock(ResponseDto::class),
            $this->createMock(ResponseDto::class),
        ];

        $this->driver
            ->expects($this->exactly(2))
            ->method('send')
            ->withConsecutive([$dtos[0]], [$dtos[1]])
            ->willReturnOnConsecutiveCalls(...$responses);

        $this->storage
            ->expects($this->once())
            ->method('store')
            ->with($responses);

        $this->assertSame($responses, $this->task->export($this->csvReader));
    }
}