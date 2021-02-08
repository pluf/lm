<?php
namespace Pluf\Lm\Process;

use Pluf\Scion\UnitTrackerInterface;
use Throwable;
use Pluf\Orm\EntityManager;

class EntityManagerTransaction
{

    public function __invoke(EntityManager $entityManager, UnitTrackerInterface $unitTracker)
    {
        $transaction = $entityManager->getTransaction();
        $transaction->begin();
        try {
            $result = $unitTracker->next([
                "entityManager" => $entityManager
            ]);
            $transaction->commit();
            return $result;
        } catch (Throwable $th) {
            $transaction->roleback();
        }
    }
}

