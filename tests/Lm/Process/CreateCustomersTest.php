<?php
namespace Pluf\Tests\Lm\Process;

use PHPUnit\Framework\TestCase;
use Pluf\Lm\Process\CreateCustomers;
use Pluf\Data\Repository;
use Pluf\Lm\Customer;

class CreateCustomersTest extends TestCase
{
    
    public function getPathAndMethodData(){
        
        return [
            [
                [// list of customers
                    new Customer(),
                    new Customer()
                ]
            ]
        ];
    }

    /**
     *
     * @dataProvider getPathAndMethodData
     * @test
     */
    public function testCreateCustomers($customers)
    {
        $counter = 0;

        // process tracker mock
        $customerRepository = $this->createMock(Repository::class);
        $customerRepository->expects($this->exactly(sizeof($customers)))
            ->method('create')
            ->willReturn($customers[$counter++]);

        $process = new CreateCustomers();
        $result = $process($customerRepository, $customers);
        $this->assertNotNull($result);
        $this->assertEquals(count($customers), count($result));
    }
}

