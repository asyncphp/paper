<?php

require __DIR__ . "/../vendor/autoload.php";

use AsyncInterop\Loop;
use AsyncPHP\Paper\Factory;

error_reporting(E_ERROR | E_PARSE);

$sample = file_get_contents(__DIR__ . "/sample.html");

$options = [
    "driver" => "prince",
    "prince" => [
        "binary" => "/opt/prince/bin/prince",
        "tempPath" => __DIR__,
        "options" => [
            "--no-compress",
            "--http-timeout" => 10,
            // https://www.princexml.com/doc/command-line/#command-line
        ],
    ]
];

$factory = new Factory();

// async

$start = microtime(true);

Loop::execute(Amp\wrap(function() use ($sample, $options, $factory) {
    $async = $factory->createDriver($options);

    yield $async
        ->html($sample)
        ->size("A4")
        ->orientation("portrait")
        ->dpi(300)
        ->render();
}));

print "async took: " . (microtime(true) - $start) . PHP_EOL;

// sync

$start = microtime(true);

$sync = $factory->createDriver($options + ["sync" => true]);

$result = $sync
    ->html($sample)
    ->size("A4")
    ->orientation("portrait")
    ->dpi(300)
    ->render();

print "sync took: " . (microtime(true) - $start) . PHP_EOL;

file_put_contents(__DIR__ . "/prince.pdf", $result);
