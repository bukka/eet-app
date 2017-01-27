<?php

namespace Bukka\EET\App\Storage;

use Bukka\EET\App\Dto\ResponseDto;

interface StorageInterface
{
    /**
     * @return void
     */
    public function close();

    /**
     * @param string $name
     * @return void
     */
    public function open($name);

    /**
     * @param ResponseDto $response
     * @return void
     */
    public function store(ResponseDto $response);
}