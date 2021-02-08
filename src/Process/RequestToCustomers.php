<?php
namespace Pluf\Lm\Process;

use Pluf\Data\Schema;
use Pluf\Lm\Customer;
use Psr\Http\Message\ServerRequestInterface;
use Pluf\Scion\UnitTrackerInterface;

class RequestToCustomers
{

    public string $injectionKey = "models";

    public function __construct()
    {
        $this->injectionKey = "customers";
    }

    public function __invoke(Schema $modelScheama, ServerRequestInterface $request, UnitTrackerInterface $unitTracker)
    {
        $models = [];
        if (self::isSingleItemInBody($request)) {
            $body = $request->getParsedBody();
            $models[] = $modelScheama->newInstance(Customer::class, $body);
        } else {
            $items = self::bodyToListOfItems($request);
            $errors = [];
            foreach ($items as $data) {
                try {
                    $models[] = $modelScheama->newInstance(Customer::class, $data);
                } catch (\Exception $ex) {
                    $errors[] = $ex;
                }
            }
            if (! empty($errors)) {
                // XXX: maso, 2021: merge and throw a new error
            }
        }
        return $unitTracker->next([
            "customers" => $models
        ]);
    }
}

