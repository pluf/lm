<?php
namespace Pluf\Lm\Process;

use Pluf\Lm\Customer;
use Pluf\Orm\EntityManager;

/**
 * Creates a list of customer and return them as result
 *
 *
 * @author maso
 *        
 */
class CreateEntities
{

    /**
     * Store list of cusomers into the repositoer
     *
     *
     * @param EntityManager $entityManager
     * @param array $customers
     *            list of customer
     * @return Customer[] list of customer
     */
    public function __invoke(EntityManager $entityManager, array $entities = []): array
    {
        $resultList = [];
        foreach ($customers as $customer) {
            $resultList[] = $entityManager->persist($customer);
        }
        return $resultList;
    }
}

