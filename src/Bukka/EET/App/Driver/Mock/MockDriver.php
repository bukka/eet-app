<?php

namespace Bukka\EET\App\Driver\Mock;

use Bukka\EET\App\Driver\DriverInterface;
use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Dto\ResponseDto;

class MockDriver implements DriverInterface
{
    /**
     * @var callable
     */
    public static $mockSender;

    /**
     * @param ReceiptDto $receiptDto
     * @return ResponseDto
     */
    public function send(ReceiptDto $receiptDto)
    {
        return call_user_func(self::$mockSender, $receiptDto);
    }
}