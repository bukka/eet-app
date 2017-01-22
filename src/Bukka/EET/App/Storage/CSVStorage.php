<?php

namespace Bukka\EET\App\Storage;

use Bukka\EET\App\CSV\CSVWriter;
use Bukka\EET\App\Dto\ResponseDto;

class CSVStorage implements StorageInterface
{
    /**
     * @var CSVWriter
     */
    private $csvWriter;

    /**
     * CSVStorage constructor
     *
     * @param CSVWriter $csvWriter
     */
    public function __construct(CSVWriter $csvWriter)
    {
        $this->csvWriter = $csvWriter;
    }

    /**
     * @param ResponseDto[] $responses
     * @return void
     */
    public function store(array $responses)
    {
        
    }
}