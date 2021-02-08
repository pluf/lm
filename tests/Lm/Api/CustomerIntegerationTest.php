<?php
namespace Pluf\Tests\Lm\Api;

use PHPUnit\Framework\TestCase;
use Pluf\Data\ModelDescriptionRepository;
use Pluf\Data\Repository;
use Pluf\Data\Loader\MapModelDescriptionLoader;
use Pluf\Data\Schema\MySQLSchema;
use Pluf\Data\Schema\SQLiteSchema;
use Pluf\Db\Connection;
use Pluf\Di\Container;
use Pluf\Di\Invoker;
use Pluf\Di\ParameterResolver\DefaultValueResolver;
use Pluf\Di\ParameterResolver\ResolverChain;
use Pluf\Di\ParameterResolver\Container\ParameterNameContainerResolver;
use Pluf\Lm\Customer;
use Pluf\Lm\Process\CreateCustomers;
use Pluf\Scion\UnitTracker;

class CustomerIntegerationTest extends TestCase
{

    public $invoker;

    public $container;

    /**
     *
     * @before
     */
    public function createServices()
    {
        $container = new Container();
        $container['dbConnection'] = Container::service(function () {
            $dbConnection = Connection::connect($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
            return $dbConnection;
        });
        $container['dataModelSchema'] = Container::service(function () {
            switch ($GLOBALS['DB_SCHEMA']) {
                case 'sqlite':
                    $dataModelSchema = new SQLiteSchema([]);
                    break;
                case 'mysql':
                    $dataModelSchema = new MySQLSchema([]);
                    break;
            }
            return $dataModelSchema;
        });
        $container['dataModelDescriptionRepository'] = Container::service(function () {
            $dataModelLoader = new MapModelDescriptionLoader([
                Customer::class => require __DIR__ . '/../../../models/CustomerMD.php'
            ]);
            $dataModelDescriptionRepository = new ModelDescriptionRepository([
                $dataModelLoader
            ]);
            return $dataModelDescriptionRepository;
        });

        $container['customerRepository'] = Container::service(function ($dbConnection, $dataModelSchema, $dataModelDescriptionRepository) {
            $repo = Repository::getInstance([
                'connection' => $dbConnection, // Connection
                'schema' => $dataModelSchema, // Schema builder (optionall)
                'mdr' => $dataModelDescriptionRepository, // storage of model descriptions (optionall)
                'model' => Customer::class
            ]);
            return $repo;
        });

        $this->invoker = new Invoker(new ResolverChain([
            new ParameterNameContainerResolver($container),
            new DefaultValueResolver()
        ]));

        /*
         * Init the db
         */
        $this->invoker->call(function ($dataModelSchema, $dbConnection, ModelDescriptionRepository $dataModelDescriptionRepository) {
            $dataModelSchema->createTables(
                // DB connection
                $dbConnection, 
                // Model description
                $dataModelDescriptionRepository->getModelDescription(Customer::class));
        });
        $this->container = $container;
    }

    /**
     *
     * @test
     */
    public function callCustoemrsCreate()
    {
        // Data
        $customers = [
            new Customer()
        ];

        // test
        $units = [
            CreateCustomers::class
        ];
        $unitTracker = new UnitTracker($units, $this->container);
        $result = $unitTracker->doProcess([
            'customers' => $customers
        ]);
        $this->assertNotNull($result);
        $this->assertEquals(count($customers), count($result));
    }
}

