<?php

namespace Bukka\EET\App\CSV;

use Bukka\EET\App\DependencyInjection\ContainerFactory;
use Bukka\EET\App\Driver\Mock\MockDriver;
use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Dto\ResponseDto;
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
     * @dataProvider getExportData
     * @param array $rows
     * @param array $expectedRows
     * @param string $fik
     * @param string $pkp
     * @param string $bkp
     */
    public function testItShouldExportCSVFile($rows, $expectedRows, $fik, $pkp, $bkp)
    {
        file_put_contents($this->inputFilePath, implode("\n", $rows));

        MockDriver::$mockSender = function (ReceiptDto $receiptDto) use ($fik, $pkp, $bkp) {
            return (new ResponseDto())
                ->setFik($fik)
                ->setPkp($pkp)
                ->setBkp($bkp)
                ->setReceipt($receiptDto);
        };

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

        $output = $commandTester->getDisplay();
        $this->assertContains('OK', $output);

        $this->assertFileExists($this->outputFilePath);
        $outputFileContent = file_get_contents($this->outputFilePath);
        $outputRows = explode("\n", rtrim($outputFileContent));
        $this->assertCount(2, $outputRows);
        $this->assertSame($expectedRows[0], $outputRows[0]);

        $outputColumns = explode(",", $outputRows[1]);
        $expectedColumns = explode(",", $expectedRows[1]);
        $expectedColumnsCount = count($expectedColumns);
        $this->assertCount($expectedColumnsCount, $outputColumns);
        for ($i = 0; $i < $expectedColumnsCount; $i++) {
            if (substr($expectedColumns[$i], 0, 1) === '[') {
                $this->assertRegExp('#' . $expectedColumns[$i] . "#", $outputColumns[$i]);
            } else {
                $this->assertSame($expectedColumns[$i], $outputColumns[$i]);
            }
        }
    }

    /**
     * @return array
     */
    public function getExportData()
    {
        $bkp = 'Ca8sTbURReQjjgcy/znXBKjPOnZof3AxWK5WySpyMrUXF0o7cz1BP6adQzktODKh2d8s' .
            'oAhn1R/S07lVDTa/6r9xTuI3NBH/+7YfYz/t92eb5Y6aNvLm6tXfOdE3C94EqmT0SEEz' .
            '9rInGXXP1whIKYX7K0HgVrxjdxCFkZF8Lt12XbahhAzJ47LcPxuBZZp6U6wJ2sWI5os3' .
            'KY9u/ZchzAUaCec7H56QwkMnu3U3Ftwi/YrxSzQZTmPTpFYKXnYanrFaLDJm+1/yg+VQ' .
            'ntoByBM+HeDXigBK+Shaxx+Nd0sSmm1Im4v685BRVdUId+4CobcnSQ3CBsjAhqmIrtWT' .
            'GQ==';

        return [
            [
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
                'b3309b52-7c87-4014-a496-4c7a53cf9125', // fik
                '03ec1d0e-6d9f77fb-1d798ccb-f4739666-a4069bc3', // pkp
                $bkp
            ]
        ];
    }
}