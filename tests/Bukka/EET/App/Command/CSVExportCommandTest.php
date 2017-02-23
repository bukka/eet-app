<?php

namespace Bukka\EET\App\CSV;

use Bukka\EET\App\DependencyInjection\ContainerFactory;
use Bukka\EET\App\Driver\Exception\DriverException;
use Bukka\EET\App\Driver\Mock\MockDriver;
use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Dto\ResponseDto;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CSVExportCommandTest extends TestCase
{
    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $fileName = 'csv_export_command.csv';

    /**
     * @var string
     */
    protected $inputBaseDir;

    /**
     * @var string
     */
    protected $inputFilePath;

    /**
     * @var string
     */
    protected $outputBaseDir;

    /**
     * @var string
     */
    protected $outputFilePath;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();

        $this->baseDir = __DIR__ . '/test/';
        if (!is_dir($this->baseDir)) {
            mkdir($this->baseDir);
        }

        $this->inputBaseDir = $this->baseDir . 'in/';
        if (!is_dir($this->inputBaseDir)) {
            mkdir($this->inputBaseDir);
        }

        $this->outputBaseDir = $this->baseDir . 'out/';
        if (!is_dir($this->outputBaseDir)) {
            mkdir($this->outputBaseDir);
        }

        $this->inputFilePath = $this->inputBaseDir . $this->fileName;
        $this->outputFilePath = $this->outputBaseDir . $this->fileName;
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        if (is_file($this->inputFilePath)) {
            unlink($this->inputFilePath);
        }
        if (is_file($this->outputFilePath)) {
            unlink($this->outputFilePath);
        }
        if (is_dir($this->inputBaseDir)) {
            rmdir($this->inputBaseDir);
        }
        if (is_dir($this->outputBaseDir)) {
            rmdir($this->outputBaseDir);
        }
        if (is_dir($this->baseDir)) {
            rmdir($this->baseDir);
        }

        parent::tearDown();
    }

    /**
     * @param array $fiks
     * @param string $pkp
     * @param string $bkp
     */
    private function mockSender($fiks = [], $pkp = '', $bkp = '')
    {
        MockDriver::$mockSender = function (ReceiptDto $receiptDto) use ($fiks, $pkp, $bkp) {
            $response = (new ResponseDto())
                ->setPkp($pkp)
                ->setBkp($bkp)
                ->setReceipt($receiptDto);

            $fik = $fiks[$receiptDto->getExternalId()];
            if ($fik === false) {
                $response->setErrorMsg("Connection error");
            } else {
                $response->setFik($fik);
            }

            return $response;
        };
    }

    /**
     * @return mixed
     */
    private function executeCommand()
    {
        $container = ContainerFactory::create(['driver' => MockDriver::class]);
        $container->setParameter('csv.reader.base.directory', $this->inputBaseDir);
        $container->setParameter('csv.writer.base.directory', $this->outputBaseDir);
        $container->setParameter('eet.service.wsdl', 'not_used.xml');
        $container->setParameter('eet.p12.cert', 'not_used.p12');
        $container->setParameter('eet.p12.password', 'eet');

        $csvExportCommand = $container->get('csv-export-command');

        $application = new Application();
        $application->add($csvExportCommand);

        $command = $application->find('csv:export');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            'path' => $this->fileName,
        ));

        return $commandTester->getDisplay();
    }

    /**
     * @param string $expectedRow
     * @param string $outputRow
     */
    private function checkOutputRow($expectedRow, $outputRow, $rowIndex)
    {
        $outputColumns = explode(",", $outputRow);
        $expectedColumns = explode(",", $expectedRow);
        $expectedColumnsCount = count($expectedColumns);
        $this->assertCount($expectedColumnsCount, $outputColumns);
        for ($i = 0; $i < $expectedColumnsCount; $i++) {
            if (substr($expectedColumns[$i], 0, 1) === '[') {
                $this->assertRegExp('#' . $expectedColumns[$i] . "#", $outputColumns[$i]);
            } else {
                $this->assertSame(
                    $expectedColumns[$i],
                    $outputColumns[$i],
                    "Failed assertion for row $rowIndex and column $i"
                );
            }
        }
    }

    /**
     * @dataProvider getExportData
     * @param array $rows
     * @param array $expectedRows
     * @param array $fiks
     * @param string $pkp
     * @param string $bkp
     */
    public function testItShouldExportCSVFile($rows, $expectedRows, $fiks, $pkp, $bkp)
    {
        file_put_contents($this->inputFilePath, implode("\n", $rows));
        $this->mockSender($fiks, $pkp, $bkp);

        $output = $this->executeCommand();
        $this->assertContains('OK', $output);

        $this->assertFileExists($this->outputFilePath);
        $outputFileContent = file_get_contents($this->outputFilePath);
        $outputRows = explode("\n", rtrim($outputFileContent));
        $expectedRowsCount = count($expectedRows);
        $this->assertCount(count($expectedRows), $outputRows);
        // check header
        $this->assertSame($expectedRows[0], $outputRows[0]);

        for ($i = 1; $i < $expectedRowsCount; $i++) {
            $this->checkOutputRow($expectedRows[$i], $outputRows[$i], $i);
        }
    }

    /**
     * @return array
     */
    public function getExportData()
    {
        $bkp = $this->getTestingBkp();

        return [
            [ // 1 valid record
                [
                    'id,dat_odesl,prvni_zadani,overeni,dic_popl,id_provoz,id_pokl,' .
                        'porad_cis,dat_trzby,celk_trzba,rezim,zakl_dan1,dan1,zakl_dan2,dan2',
                    '1,"10.1.2017 9:10:01",ano,"ano",CZ24222224,101,3,5862,"9.1.2017",100,0,83,17,80,60.5'
                ],
                [
                    'id,dat_odesl,prvni_zadani,overeni,dic_popl,id_provoz,id_pokl,porad_cis,dat_trzby,celk_trzba,' .
                        'rezim,zakl_dan1,dan1,zakl_dan2,dan2,uuid_zpravy,fik,pkp,bkp,chyba',
                    '1,"10.1.2017 9:10:01",ano,ano,CZ24222224,101,3,5862,9.1.2017,100,0,83,17,80,60.5,' .
                        '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12},' .
                        'b3309b52-7c87-4014-a496-4c7a53cf9125,03ec1d0e-6d9f77fb-1d798ccb-f4739666-a4069bc3,' .
                        $bkp . ','
                ],
                [1 => 'b3309b52-7c87-4014-a496-4c7a53cf9125'], // fiks
                '03ec1d0e-6d9f77fb-1d798ccb-f4739666-a4069bc3', // pkp
                $bkp
            ],
            [ // 2 records with first invalid (missing dic_popl)
                [
                    'id,dat_odesl,prvni_zadani,overeni,dic_popl,id_provoz,id_pokl,' .
                    'porad_cis,dat_trzby,celk_trzba,rezim,zakl_dan1,dan1,zakl_dan2,dan2',
                    '1,"10.1.2017 9:11:01",ano,"ano",,103,3,5862,"9.1.2017",100,0,83,17,80,60.5', // missing dic_popl
                    '2,"10.1.2017 9:10:01",ano,"ano",CZ24222224,101,3,5862,"9.1.2017",100,0,83,17,80,60.5', // OK
                ],
                [
                    'id,dat_odesl,prvni_zadani,overeni,dic_popl,id_provoz,id_pokl,porad_cis,dat_trzby,celk_trzba,' .
                    'rezim,zakl_dan1,dan1,zakl_dan2,dan2,uuid_zpravy,fik,pkp,bkp,chyba',
                    '1,"10.1.2017 9:11:01",ano,ano,,103,3,5862,9.1.2017,100,0,83,17,80,60.5,' .
                        '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12},,,,' .
                        '"Missing or invalid field: dic_popl"',
                    '2,"10.1.2017 9:10:01",ano,ano,CZ24222224,101,3,5862,9.1.2017,100,0,83,17,80,60.5,' .
                        '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12},' .
                        'b3309b52-7c87-4014-a496-4c7a53cf9125,03ec1d0e-6d9f77fb-1d798ccb-f4739666-a4069bc3,' .
                        $bkp . ','
                ],
                [2 => 'b3309b52-7c87-4014-a496-4c7a53cf9125'], // fiks
                '03ec1d0e-6d9f77fb-1d798ccb-f4739666-a4069bc3', // pkp
                $bkp
            ],
            [ // 2 records with faling connection for second
                [
                    'id,dat_odesl,prvni_zadani,overeni,dic_popl,id_provoz,id_pokl,' .
                        'porad_cis,dat_trzby,celk_trzba,rezim,zakl_dan1,dan1,zakl_dan2,dan2',
                    '1,"10.1.2017 9:11:01",ano,"ano",CZ24222224,103,3,5862,"9.1.2017",100,0,83,17,80,60.5', // missing dic_popl
                    '2,"10.1.2017 9:10:01",ano,"ano",CZ24222224,101,3,5862,"9.1.2017",100,0,83,17,80,60.5', // OK
                ],
                [
                    'id,dat_odesl,prvni_zadani,overeni,dic_popl,id_provoz,id_pokl,porad_cis,dat_trzby,celk_trzba,' .
                        'rezim,zakl_dan1,dan1,zakl_dan2,dan2,uuid_zpravy,fik,pkp,bkp,chyba',
                    '1,"10.1.2017 9:11:01",ano,ano,CZ24222224,103,3,5862,9.1.2017,100,0,83,17,80,60.5,' .
                        '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12},' .
                        ',03ec1d0e-6d9f77fb-1d798ccb-f4739666-a4069bc3,' . $bkp . ',"Connection error"',
                    '2,"10.1.2017 9:10:01",ano,ano,CZ24222224,101,3,5862,9.1.2017,100,0,83,17,80,60.5,' .
                        '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12},' .
                        'b3309b52-7c87-4014-a496-4c7a53cf9125,03ec1d0e-6d9f77fb-1d798ccb-f4739666-a4069bc3,' .
                        $bkp . ','
                ],
                [1 => false, 2 => 'b3309b52-7c87-4014-a496-4c7a53cf9125'], // fiks
                '03ec1d0e-6d9f77fb-1d798ccb-f4739666-a4069bc3', // pkp
                $bkp
            ]
        ];
    }

    /**
     * @return string
     */
    private function getTestingBkp()
    {
        return 'Ca8sTbURReQjjgcy/znXBKjPOnZof3AxWK5WySpyMrUXF0o7cz1BP6adQzktODKh2d8s' .
            'oAhn1R/S07lVDTa/6r9xTuI3NBH/+7YfYz/t92eb5Y6aNvLm6tXfOdE3C94EqmT0SEEz' .
            '9rInGXXP1whIKYX7K0HgVrxjdxCFkZF8Lt12XbahhAzJ47LcPxuBZZp6U6wJ2sWI5os3' .
            'KY9u/ZchzAUaCec7H56QwkMnu3U3Ftwi/YrxSzQZTmPTpFYKXnYanrFaLDJm+1/yg+VQ' .
            'ntoByBM+HeDXigBK+Shaxx+Nd0sSmm1Im4v685BRVdUId+4CobcnSQ3CBsjAhqmIrtWT' .
            'GQ==';

    }
}