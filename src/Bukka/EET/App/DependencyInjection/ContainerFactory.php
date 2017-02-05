<?php

namespace Bukka\EET\App\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ContainerFactory
{
    /**
     * Mapping of used services names to classes
     *
     * It does not allow a full customization but it might be replaced with classes
     * that have got the same dependencies (e.g. test usage).
     *
     * @var array
     */
    private static $defaultMapping = [
        'csv-export-command'                => 'Bukka\EET\App\Command\CSVExportCommand',
        'csv-export-task'                   => 'Bukka\EET\App\Task\CSVExportTask',
        'csv-reader'                        => 'Bukka\EET\App\CSV\CSVReader',
        'csv-writer'                        => 'Bukka\EET\App\CSV\CSVWriter',
        'csv-storage'                       => 'Bukka\EET\App\Storage\CSVStorage',
        'driver-ondrejnov'                  => 'Bukka\EET\App\Driver\Ondrejnov\Driver',
        'transformer-array-to-receipt-dto'  => 'Bukka\EET\App\Transformer\ArrayToReceiptDtoTransformer',
        'transformer-response-dto-to-array' => 'Bukka\EET\App\Transformer\ResponseDtoToArrayTransformer',
        'validator-required-receipt-fields' => 'Bukka\EET\App\Validator\RequiredReceiptFieldsValidator',
    ];

    /**
     * Create a new container from mapping
     *
     * @param array $customMapping
     * @return ContainerBuilder
     */
    public static function create(array $customMapping = [])
    {
        $mapping = array_merge(self::$defaultMapping, $customMapping);

        $container = new ContainerBuilder();

        $container
            ->register('csv-export-command', $mapping['csv-export-command'])
            ->addMethodCall('setTask', [new Reference('csv-export-task')]);

        $container
            ->register('csv-export-task', $mapping['csv-export-task'])
            ->addArgument(new Reference('transformer-array-to-receipt-dto'))
            ->addArgument(new Reference('validator-required-receipt-fields'))
            ->addArgument(new Reference('driver-ondrejnov'))
            ->addArgument(new Reference('csv-storage'));

        $container
            ->register('csv-reader', $mapping['csv-reader'])
            ->addArgument('%csv.reader.base.directory%');

        $container
            ->register('csv-writer', $mapping['csv-writer'])
            ->addArgument('%csv.writer.base.directory%');

        $container
            ->register('csv-storage', $mapping['csv-storage'])
            ->addArgument(new Reference('csv-writer'))
            ->addArgument(new Reference('transformer-response-dto-to-array'));

        $container
            ->register('driver-ondrejnov', $mapping['driver-ondrejnov']);

        $container
            ->register('transformer-array-to-receipt-dto', $mapping['transformer-array-to-receipt-dto']);

        $container
            ->register('transformer-response-dto-to-array', $mapping['transformer-response-dto-to-array']);

        $container
            ->register('validator-required-receipt-fields', $mapping['validator-required-receipt-fields']);


        return $container;
    }
}