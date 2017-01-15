<?php

namespace Bukka\EET\App\Driver;

use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Dto\ResponseDto;

interface DriverInterface
{
    /**
     * @param ReceiptDto $receiptDto
     * @return ResponseDto
     */
    public function send(ReceiptDto $receiptDto);
}