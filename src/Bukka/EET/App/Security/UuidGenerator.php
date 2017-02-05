<?php

namespace Bukka\EET\App\Security;

use Ramsey\Uuid\Uuid;

class UuidGenerator
{
    /**
     * @return string
     */
    public function generate()
    {
        return Uuid::uuid4();
    }
}