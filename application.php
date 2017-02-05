<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bukka\EET\App\DependencyInjection\ContainerFactory;
use Symfony\Component\Console\Application;

$csvBaseDir = __DIR__ . '/csv/';

$container = ContainerFactory::create();
$container->setParameter('csv.reader.base.directory', $csvBaseDir . 'in/');
$container->setParameter('csv.writer.base.directory', $csvBaseDir . 'out/');

$application = new Application();
$application->add($container->get('csv-export-command'));

$application->run();