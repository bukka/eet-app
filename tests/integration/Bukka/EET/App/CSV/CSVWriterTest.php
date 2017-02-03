<?php

namespace Bukka\EET\App\CSV;

class CSVWriterTest extends CSVTestCase
{
    /**
     * @var string
     */
    protected $fileName = 'csv_write_test.csv';

    /**
     * @dataProvider getRowsDataProvider
     * @param array $rows
     * @param array $expectedRows
     */
    public function testItShouldWriteCSVFile($expectedRows, $rows)
    {
        $writer = new CSVWriter($this->baseDir);
        $writer->create($this->fileName);

        foreach ($rows as $row) {
            $writer->insert($row);
        }
        $writer->close();

        $this->assertFileExists($this->filePath);
        $this->assertSame(implode("\n", $expectedRows) . "\n", file_get_contents($this->filePath));
    }

    /**
     * @return array
     */
    public function getRowsDataProvider()
    {
        return [
            [
                [
                    'uuid,name,value',
                    '1223,test,"this is a value"'
                ],
                [
                    ['uuid' => '1223', 'name' => 'test', 'value' => 'this is a value']
                ]
            ]
        ];
    }
}