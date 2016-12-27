<?php

require_once __DIR__ . '/bootstrap.php';

use Bukka\EET\App\Command\RunCommand;

use Symfony\Component\Console\Application;

$runCommand = new RunCommand();

$application = new Application();
$application->add($runCommand);

$application->run();