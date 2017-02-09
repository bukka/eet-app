<?php

namespace Bukka\EET\App\Command;

use Bukka\EET\App\CSV\CSVReader;
use Bukka\EET\App\Task\CSVExportTask;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CSVExportCommand extends Command
{
    /**
     * @var CSVExportTask
     */
    private $task;

    /**
     * @var CSVReader
     */
    private $csvReader;

    /**
     * @param CSVExportTask $task
     */
    public function setTask(CSVExportTask $task)
    {
        $this->task = $task;
    }

    /**
     * @param CSVReader $csvReader
     */
    public function setCSVReader(CSVReader $csvReader)
    {
        $this->csvReader = $csvReader;
    }

    /**
     * Configures the CSV export command.
     */
    protected function configure()
    {
        $this
            ->setName('csv:export')
            ->setDescription('Export CSV file.')
            ->setHelp("This command allows to export CSV file to EET")
            ->addArgument('path', InputArgument::REQUIRED, 'The path of the CSV file.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        $this->csvReader->create($path);
        $this->task->export($this->csvReader);
    }
}