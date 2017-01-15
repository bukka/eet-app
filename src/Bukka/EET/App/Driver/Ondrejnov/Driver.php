<?php

namespace Bukka\EET\App\Driver\Ondrejnov;

use Bukka\EET\App\Driver\DriverInterface;
use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Dto\ResponseDto;

class Driver implements DriverInterface
{
    /**
     * @param ReceiptDto $receiptDto
     * @return ResponseDto
     */
    public function send(ReceiptDto $receiptDto)
    {

    }
}