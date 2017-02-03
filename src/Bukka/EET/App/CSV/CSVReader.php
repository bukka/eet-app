<?php

namespace Bukka\EET\App\CSV;

use League\Csv\Reader;

class CSVReader
{
    /**
     * @var string
     */
    private $baseDirectory;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var array
     */
    private $headerRow;

    /**
     * CSVReader constructor
     *
     * @param string $csvBaseDirectory
     */
    public function __construct($csvBaseDirectory)
    {
        $this->baseDirectory = $csvBaseDirectory;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function create($name)
    {
        $this->name = $name;
        $this->reader = Reader::createFromPath($this->baseDirectory . $name);
        $this->headerRow = null;
    }

    /**
     * @return \Iterator
     */
    public function fetch()
    {
        return $this->reader->fetchAssoc();
    }
}