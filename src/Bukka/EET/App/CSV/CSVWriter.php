<?php

namespace Bukka\EET\App\CSV;

use League\Csv\Writer;

class CSVWriter
{
    /**
     * @var string
     */
    private $baseDirectory;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @var Writer
     */
    private $writer;

    private $headerInserted;

    /**
     * CSVWriter constructor
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $name
     * @return void
     */
    public function create($name)
    {
        $this->path = $this->baseDirectory . $name;
        $this->writer = Writer::createFromPath(new \SplFileObject($this->path, 'a+'), 'w');
        $this->headerInserted = false;
    }

    /**
     * @return void
     */
    public function close()
    {
        $this->path = $this->writer = null;
    }


    /**
     * @param array $row
     */
    public function insert($row)
    {
        if (!$this->headerInserted) {
            $this->writer->insertOne(array_keys($row));
            $this->headerInserted = true;
        }

        $this->writer->insertOne($row);
    }
}