<?php
namespace Pluf\Lm\Process;

use Pluf\Scion\UnitTrackerInterface;

class EntityManagerFactory
{

    public function __invoke(\Pluf\Orm\EntityManagerFactory $entityManagerFactory, UnitTrackerInterface $unitTracker)
    {
        $entityManager = $entityManagerFactory->create();
        try {
            return $unitTracker->next([
                "entityManager" => $entityManager
            ]);
        } finally {
            $entityManager->close();
        }
    }
}

