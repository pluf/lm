<?php
use Pluf\Di;
use Pluf\Http\StreamFactory;
use Pluf\Log\Logger;
use Pluf\Orm\EntityManagerFactoryBuilder;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Orm\ObjectValidatorBuilder;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;

/*
 * The container:
 *
 * This is a place to store list of services and load them if required.
 */
$container = new Di\Container();

/*
 * Logs
 * ********************************************************************************
 *
 * Services:
 *
 * - logger
 * ********************************************************************************
 */
$container->addService('logger', function () {
    $logger = Logger::getLogger('lm');
    return $logger;
});

/*
 * IO
 * ********************************************************************************
 *
 * Services:
 *
 * - streamFactory
 * ********************************************************************************
 */
$container->addService('streamFactory', function () {
    $streamFactory = new StreamFactory();
    return $streamFactory;
});

/*
 * ORM (Entity manager)
 * ********************************************************************************
 *
 * Services:
 *
 * - entityManagerFactory
 * - dbConnection
 * - objectValidator
 * - objectMapper
 * - modelDescriptionRepository
 * ********************************************************************************
 */
$container->addService('entityManagerFactory', function ($dbConnection, $modelDescriptionRepository) {
    $builder = new EntityManagerFactoryBuilder();
    $factory = $builder->setConnection($dbConnection)
        ->setModelDescriptionRepository($modelDescriptionRepository)
        ->build();
    return $factory;
});

$container->addService('dbConnection', function () {
    $dbConnection = Connection::connect("sqlite::memory:", "root", "");
    return $dbConnection;
});

$container->addService('objectValidator', function () {
    $builder = new ObjectValidatorBuilder();
    $objectValidator = $builder->build();
    return $objectValidator;
});

$container->addService('objectMapper', function () {
    $builder = new ObjectMapperBuilder();
    $objectMapper = $builder->addType('json')
        ->addType('csv')
        ->addType('array')
        ->build();
    return $objectMapper;
});

$container->addService('modelDescriptionRepository', function(){
    $modelDescriptionRepository = new ModelDescriptionRepository([
        new ModelDescriptionLoaderAttribute()
    ]);
    return $modelDescriptionRepository;
});

return $container;
