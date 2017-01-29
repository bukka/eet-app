<?php

namespace Bukka\EET\App\Task;

use Bukka\EET\App\CSV\CSVReader;
use Bukka\EET\App\Driver\DriverInterface;
use Bukka\EET\App\Dto\ResponseDto;
use Bukka\EET\App\Storage\StorageInterface;
use Bukka\EET\App\Transformer\ArrayToReceiptDtoTransformer;
use Bukka\EET\App\Validator\ReceiptValidatorInterface;

class CSVExportTask
{
    /**
     * @var ArrayToReceiptDtoTransformer
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
     * @param ArrayToReceiptDtoTransformer $receiptTransformer
     * @param ReceiptValidatorInterface $receiptValidator
     * @param DriverInterface $driver
     * @param StorageInterface $storage
     */
    public function __construct(
        ArrayToReceiptDtoTransformer $receiptTransformer,
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
        $response = $this->driver->send($dto);
        $this->storage->add($response);

        return $response;
    }

    /**
     * @param CSVReader $csvReader
     * @return ResponseDto[]
     */
    public function export(CSVReader $csvReader)
    {
        $this->storage->open($csvReader->getName());
        foreach ($csvReader->fetch() as $row) {
            $this->exportRow($row);
        }

        return $this->storage->save();
    }
}