<?php

namespace Bukka\EET\App\Task;

use Bukka\EET\App\CSV\CSVReader;
use Bukka\EET\App\Driver\DriverInterface;
use Bukka\EET\App\Dto\ResponseDto;
use Bukka\EET\App\Storage\StorageInterface;
use Bukka\EET\App\Storage\StorageResult;
use Bukka\EET\App\Transformer\ArrayToReceiptDtoTransformer;
use Bukka\EET\App\Validator\Exception\ValidatorException;
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
     * @var array
     */
    private $errors;

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
        $receipt = $this->receiptTransformer->transform($row);
        try {
            $this->receiptValidator->validate($receipt);
            $response = $this->driver->send($receipt);
        } catch (\Exception $exception) {
            $response = (new ResponseDto())
                ->setReceipt($receipt)
                ->setErrorCode($exception->getCode())
                ->setErrorMsg($exception->getMessage());
        }

        $this->storage->add($response);

        return $response;
    }

    /**
     * @param CSVReader $csvReader
     * @return StorageResult
     */
    public function export(CSVReader $csvReader)
    {
        $this->storage->open($csvReader->getName());
        $this->errors = [];
        foreach ($csvReader->fetch() as $row) {
            $this->exportRow($row);
        }

        return $this->storage->save();

    }
}