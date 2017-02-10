<?php

namespace Bukka\EET\App\CSV;

class CSVReaderTest extends CSVTestCase
{
    /**
     * @var string
     */
    protected $fileName = 'csv_read_test.csv';

    /**
     * @dataProvider getRowsDataProvider
     * @param array $rows
     * @param array $expectedRows
     */
    public function testItShouldReadCSVFile($rows, $expectedRows)
    {
        file_put_contents($this->filePath, implode("\n", $rows));
        $reader = new CSVReader($this->baseDir);
        $reader->create($this->fileName);
        $i = 0;
        foreach ($reader->fetch() as $row) {
            $this->assertSame($expectedRows[$i], $row);
        }
    }

    /**
     * @return array
     */
    public function getRowsDataProvider()
    {
        return [
            [
                [
                    'uuid,name,"value"',
                    '1223,test,"this is a value"'
                ],
                [
                    ['uuid' => '1223', 'name' => 'test', 'value' => 'this is a value']
                ]
            ]
        ];
    }
}