<?php

namespace Bukka\EET\App\Transformer;

use Bukka\EET\App\Dto\ReceiptDto;

interface ReceiptTransformerInterface
{
    /**
     * @param mixed $data
     * @return ReceiptDto
     */
    public function transform($data);
}