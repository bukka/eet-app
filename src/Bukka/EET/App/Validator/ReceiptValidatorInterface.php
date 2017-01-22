<?php

namespace Bukka\EET\App\Validator;

use Bukka\EET\App\Dto\ReceiptDto;

interface ReceiptValidatorInterface
{
    /**
     * @param ReceiptDto $receiptDto
     * @return void
     */
    public function validate(ReceiptDto $receiptDto);
}