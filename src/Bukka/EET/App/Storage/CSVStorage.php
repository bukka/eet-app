<?php

namespace Bukka\EET\App\Storage;

use Bukka\EET\App\CSV\CSVWriter;
use Bukka\EET\App\Dto\ResponseDto;
use Bukka\EET\App\Transformer\ResponseDtoToArrayTransformer;

class CSVStorage implements StorageInterface
{
    /**
     * @var CSVWriter
     */
    private $csvWriter;

    /**
     * @var ResponseDtoToArrayTransformer
     */
    private $transformer;

    /**
     * CSVStorage constructor
     *
     * @param CSVWriter $csvWriter
     * @param ResponseDtoToArrayTransformer $transformer
     */
    public function __construct(CSVWriter $csvWriter, ResponseDtoToArrayTransformer $transformer)
    {
        $this->csvWriter = $csvWriter;
        $this->transformer = $transformer;
    }

    /**
     * @return void
     */
    public function close()
    {

    }

    /**
     * @param string $name
     * @return void
     */
    public function open($name)
    {

    }

    /**
     * @param ResponseDto $response
     * @return void
     */
    public function store(ResponseDto $response)
    {

    }
}