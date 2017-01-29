<?php

namespace Bukka\EET\App\Storage;

use Bukka\EET\App\Dto\ResponseDto;

interface StorageInterface
{
    /**
     * @param ResponseDto $response
     * @return void
     */
    public function add(ResponseDto $response);

    /**
     * @param string $name
     * @return void
     */
    public function open($name);

    /**
     * @return void
     */
    public function save();
}