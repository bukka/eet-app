<?php

namespace Bukka\EET\App\Dto;

class ResponseDto
{
    /**
     * @var string
     */
    private $fik;

    /**
     * @var ReceiptDto
     */
    private $receipt;

    /**
     * @return string
     */
    public function getFik()
    {
        return $this->fik;
    }

    /**
     * @param string $fik
     */
    public function setFik($fik)
    {
        $this->fik = $fik;
    }

    /**
     * @return ReceiptDto
     */
    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * @param ReceiptDto $receipt
     */
    public function setReceipt($receipt)
    {
        $this->receipt = $receipt;
    }
}