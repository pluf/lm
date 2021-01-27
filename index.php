<?php
require_once 'vendoer/autoload.php';
use Pluf\Lm;
use Pluf\Http;
use Pluf\Di;

$units = [
    Http\Process\AccessLog::class,
    Http\Process\Coder::class,
    Http\Process\CatchException::class,
    [
        new Http\Process\IfPathIs('^/licenses'), 
        [
            Http\Process\IfMethodIsGet::class,
            Lm\Process\FindLicenses::class
        ],
        [
            Http\Process\IfMethodIsPost::class,
            Lm\Process\CreateLicenses::class
        ],
        [
            Http\Process\IfMethodIsDelete::class,
            Lm\Process\DeleteLicenses::class
        ],
    ],
    [
        new Http\Process\IfPathIs('^/licenses/(?P<id>\d+)'),
        Lm\Process\LoadLicense::class,
        [
            Http\Process\IfMethodIsGet::class,
            Lm\Process\ReturnLicense::class
        ],
        [
            Http\Process\IfMethodIsDelete::class,
            Lm\Process\DeleteLicense::class
        ],
        [
            Http\Process\IfMethodIsPost::class,
            Lm\Process\UpdateLicense::class
        ],
    ]
];

$container = new Di\Container();
$container->addService('logger', function(){
    $logger = Logger::get('lm');
    return $logger;
});