<?php

namespace Bukka\EET\App\Driver\Ondrejnov;

use Bukka\EET\App\Driver\DriverInterface;
use Bukka\EET\App\Driver\Exception\DriverException;
use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Dto\ResponseDto;

class Driver implements DriverInterface
{
    /**
     * @var string
     */
    private $serviceWSDL;

    /**
     * @var string
     */
    private $p12Cert;

    /**
     * @var string
     */
    private $password;

    /**
     * Driver constructor
     *
     * @param string $serviceWSDL
     * @param string $p12Cert
     * @param string $password
     */
    public function __construct($serviceWSDL, $p12Cert, $password)
    {
        $this->serviceWSDL = $serviceWSDL;
        $this->p12Cert = $p12Cert;
        $this->password = $password;
    }

    /**
     * @param ReceiptDto $receiptDto
     * @return ResponseDto
     * @throws DriverException
     */
    public function send(ReceiptDto $receiptDto)
    {
        if (!is_file($this->p12Cert)) {
            throw new DriverException('The PKCS12 certificate path is invalid');
        }

        if (!openssl_pkcs12_read(file_get_contents($this->p12Cert), $cert, $this->password)) {
            throw new DriverException('Invalid PKCS12 certificate');
        }

        $dispatcher = new Dispatcher($this->serviceWSDL, $cert['pkey'], $cert['cert']);

        return $dispatcher->send($receiptDto);
    }
}