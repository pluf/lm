<?php
require_once 'vendoer/autoload.php';

use Pluf\Di;
use Pluf\Http;
use Pluf\Lm;
use Pluf\Log\Logger;
use Pluf\Scion\Process;

$units = [
    Process\Http\AccessLog::class,
    Process\Http\Coder::class,
    Process\Http\CatchException::class,
    [
        new Process\Http\IfPathIs('^/licenses'), 
        [
            Process\Http\IfMethodIsGet::class,
            Lm\Process\FindLicenses::class
        ],
        [
            Process\Http\IfMethodIsPost::class,
            Lm\Process\CreateLicenses::class
        ],
        [
            Process\Http\IfMethodIsDelete::class,
            Lm\Process\DeleteLicenses::class
        ],
    ],
    [
        new Process\Http\IfPathIs('^/licenses/(?P<id>\d+)'),
        Lm\Process\LoadLicense::class,
        [
            Process\Http\IfMethodIsGet::class,
            Lm\Process\ReturnLicense::class
        ],
        [
            Process\Http\IfMethodIsDelete::class,
            Lm\Process\DeleteLicense::class
        ],
        [
            Process\Http\IfMethodIsPost::class,
            Lm\Process\UpdateLicense::class
        ],
    ]
];

$container = new Di\Container();
$container->addService('logger', function(){
    $logger = Logger::get('lm');
    return $logger;
});