<?php

namespace Bukka\EET\App\Storage;

use Bukka\EET\App\Dto\ResponseDto;

interface StorageInterface
{
    /**
     * @param ResponseDto[] $responses
     * @return void
     */
    public function store(array $responses);
}