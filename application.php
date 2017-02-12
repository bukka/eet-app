<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bukka\EET\App\DependencyInjection\ContainerFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\Yaml\Yaml;

$parametersFile = __DIR__ . '/config/parameters.yml';
if (!is_file($parametersFile)) {
    die("Parameters file '$parametersFile' doesn not exist");
}

$container = ContainerFactory::create();

$config = Yaml::parse(file_get_contents($parametersFile));
foreach ($config['parameters'] as $parameterName => $parameterValue) {
    $container->setParameter($parameterName, str_replace('%app.root_dir%', __DIR__, $parameterValue));
}

$application = new Application();
$application->add($container->get('csv-export-command'));

$application->run();