<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Pluf\Options;
use Pluf\Data\ModelDescriptionRepository;
use Pluf\Data\Repository;
use Pluf\Data\Loader\MapModelDescriptionLoader;
use Pluf\Data\Schema\MySQLSchema;
use Pluf\Data\Schema\SQLiteSchema;
use Pluf\Db\Connection;
use Pluf\Di\Container;
use Pluf\Http\HttpResponseEmitter;
use Pluf\Http\ResponseFactory;
use Pluf\Http\ServerRequestFactory;
use Pluf\Scion\UnitTracker;

// *****************************************************************
// Loading unit tracker
// *****************************************************************
$units = include __DIR__ . '/units.php';;
$container = include __DIR__ . '/boot.php';
$unitTracker = new UnitTracker($units, $container);


// *****************************************************************
// Process the input request with UnitManager
// *****************************************************************
$responseFactory = new ResponseFactory();
$httpResponseEmitter = new HttpResponseEmitter();
$response = $unitTracker->doProcess([
    'request' => ServerRequestFactory::createFromGlobals(),
    'response' => $responseFactory->createResponse(200, 'Success'),
    'responseFactory' => $responseFactory
]);
$httpResponseEmitter->emit($response);

