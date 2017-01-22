<?php

namespace Bukka\EET\App\Task;

use Bukka\EET\App\CSV\CSVReader;
use Bukka\EET\App\Driver\DriverInterface;
use Bukka\EET\App\Dto\ResponseDto;
use Bukka\EET\App\Storage\StorageInterface;
use Bukka\EET\App\Transformer\ReceiptTransformerInterface;
use Bukka\EET\App\Validator\ReceiptValidatorInterface;

class CSVExportTask
{
    /**
     * @var ReceiptTransformerInterface
     */
    private $receiptTransformer;

    /**
     * @var ReceiptValidatorInterface
     */
    private $receiptValidator;

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * CSVExportTask constructor
     *
     * @param ReceiptTransformerInterface $receiptTransformer
     * @param ReceiptValidatorInterface $receiptValidator
     * @param DriverInterface $driver
     * @param StorageInterface $storage
     */
    public function __construct(
        ReceiptTransformerInterface $receiptTransformer,
        ReceiptValidatorInterface $receiptValidator,
        DriverInterface $driver,
        StorageInterface $storage
    ) {
        $this->receiptTransformer = $receiptTransformer;
        $this->receiptValidator = $receiptValidator;
        $this->driver = $driver;
        $this->storage = $storage;
    }

    /**
     * @param array $row
     * @return ResponseDto
     */
    private function exportRow(array $row)
    {
        $dto = $this->receiptTransformer->transform($row);
        $this->receiptValidator->validate($dto);

        return $this->driver->send($dto);
    }

    /**
     * @param CSVReader $csvReader
     * @return ResponseDto[]
     */
    public function export(CSVReader $csvReader)
    {
        $responses = [];
        foreach ($csvReader->fetch() as $row) {
            $responses[] = $this->exportRow($row);
        }
        $this->storage->store($responses);

        return $responses;
    }
}