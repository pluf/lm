<?php
namespace Pluf\Tests\Lm\Data;

use PHPUnit\Framework\TestCase;
use Pluf\Data\ModelDescriptionRepository;
use Pluf\Data\Query;
use Pluf\Data\Repository;
use Pluf\Data\Schema;
use Pluf\Data\Loader\MapModelDescriptionLoader;
use Pluf\Data\Schema\MySQLSchema;
use Pluf\Data\Schema\SQLiteSchema;
use Pluf\Db\Connection;
use Pluf\Lm\Customer;

class CustomerDataTest extends TestCase
{

    public ?Connection $connection;

    public ?Schema $schema;

    public $modelLoader;

    public $mdr;

    /**
     *
     * @before
     */
    public function createServices()
    {
        $this->connection = Connection::connect($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
        switch ($GLOBALS['DB_SCHEMA']) {
            case 'sqlite':
                $this->schema = new SQLiteSchema([]);
                break;
            case 'mysql':
                $this->schema = new MySQLSchema([]);
                break;
        }
        $this->modelLoader = new MapModelDescriptionLoader([
            Customer::class => require __DIR__ . '/../../../models/CustomerMD.php'
        ]);

        $this->mdr = new ModelDescriptionRepository([
            $this->modelLoader
        ]);

        $this->schema->createTables(
            // DB connection
            $this->connection, 
            // Model description
            $this->mdr->getModelDescription(Customer::class));
    }

    /**
     *
     * @after
     */
    public function deleteApplication()
    {
        // $m = new Pluf_Migration();
        // $m->uninstall();
        $this->schema->dropTables(
            // DB connection
            $this->connection, 
            // Model description
            $this->mdr->getModelDescription(Customer::class));
    }

    /**
     * Getting list of books with repository model
     *
     * @test
     */
    public function getListOfCustomersByOptions()
    {
        $repo = Repository::getInstance([
            'connection' => $this->connection, // Connection
            'schema' => $this->schema, // Schema builder (optionall)
            'mdr' => $this->mdr, // storage of model descriptions (optionall)
            'model' => Customer::class
        ]);
        $this->assertNotNull($repo);
        $query = new Query([
            'filter' => [
                [
                    'title',
                    '=',
                    'my title'
                ],
                [
                    'id',
                    '>',
                    5
                ]
            ]
        ]);
        $items = $repo->get($query);
        $this->assertNotNull($items);
    }

    /**
     *
     * @test
     */
    public function putCustomersByOptionsModel()
    {
        $repo = Repository::getInstance([
            'connection' => $this->connection, // Connection
            'schema' => $this->schema, // Schema builder (optionall)
            'mdr' => $this->mdr, // storage of model descriptions (optionall)
            'model' => Customer::class
        ]);
        $this->assertNotNull($repo);

        $book = new Customer();
        $book->title = 'Hi';
        $book->description = 'Hi';
        $repo->create($book);
        $this->assertTrue(isset($book->id));

        $items = $repo->get();
        $this->assertNotNull($items);
        $this->assertTrue(count($items) > 0);
    }
}

