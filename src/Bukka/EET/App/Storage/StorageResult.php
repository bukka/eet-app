<?php

namespace Bukka\EET\App\Storage;

class StorageResult
{
    /**
     * @var array
     */
    private $info = [];

    /**
     * StorageResult constructor
     */
    public function __construct($info)
    {
        $this->info = $info;
    }

    /**
     * @param null|string $section
     * @param null|string $subsection
     * @return array|mixed|null
     */
    public function getInfo($section = null, $subsection = null)
    {
        if ($section === null) {
            return $this->info;
        }
        if (!isset($this->info[$section])) {
            return null;
        }
        if ($subsection === null) {
            return $this->info[$section];
        }

        return isset($this->info[$section][$subsection]) ? $this->info[$section][$subsection] : null;
    }
}