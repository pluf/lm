<?php

use Pluf\Di;
use Pluf\Http;
use Pluf\Lm;
use Pluf\Log\Logger;
use Pluf\Scion\Process;

return [
    Process\Http\AccessLog::class,
    Process\Http\RequestBodyParser::class,
    Process\Http\CatchException::class,
    [
        new Process\Http\IfPathAndMethodIs('#^/customers#', ['GET', 'POST','DELETE']),
        Lm\Process\EntityManagerFactory::class,
        [
            Process\Http\IfMethodIsGet::class,
            Lm\Process\FindLicenses::class
        ],
        [
            Process\Http\IfMethodIsPost::class,
            Lm\Process\EntityManagerTransaction::class,
            Lm\Process\CreateEntities::class
        ],
        [
            Process\Http\IfMethodIsDelete::class,
            Lm\Process\EntityManagerTransaction::class,
            Lm\Process\DeleteLicenses::class
        ],
    ],
    Process\Http\ResourceNotFoundException::class
];