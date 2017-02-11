<?php

namespace Bukka\EET\App\Dto;

class ResponseDto
{
    /**
     * @var string
     */
    private $fik;

    /**
     * @var string
     */
    private $pkp;

    /**
     * @var string
     */
    private $bkp;

    /**
     * @var int
     */
    private $errorCode;

    /**
     * @var string|null
     */
    private $errorMsg;

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
     * @return ResponseDto
     */
    public function setFik($fik)
    {
        $this->fik = $fik;
        return $this;
    }

    /**
     * @return string
     */
    public function getPkp()
    {
        return $this->pkp;
    }

    /**
     * @param string $pkp
     * @return ResponseDto
     */
    public function setPkp($pkp)
    {
        $this->pkp = $pkp;
        return $this;
    }

    /**
     * @return string
     */
    public function getBkp()
    {
        return $this->bkp;
    }

    /**
     * @param string $bkp
     * @return ResponseDto
     */
    public function setBkp($bkp)
    {
        $this->bkp = $bkp;
        return $this;
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param int $errorCode
     * @return ResponseDto
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * @param null|string $errorMsg
     * @return ResponseDto
     */
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
        return $this;
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
     * @return ResponseDto
     */
    public function setReceipt($receipt)
    {
        $this->receipt = $receipt;
        return $this;
    }
}