<?php

require __DIR__ . "/../vendor/autoload.php";

use AsyncInterop\Loop;
use AsyncPHP\Paper\Factory;

error_reporting(E_ERROR | E_PARSE);

// this too could be async...
$sample = file_get_contents(__DIR__ . "/sample.html");

Loop::execute(Amp\wrap(function() use ($sample) {
    Loop::repeat(10, function() {
        print ".";
    });

    $factory = new Factory();

    $driver = $factory->createDriver([
        "driver" => "webkit",
    ]);

    $runner = $factory->createRunner([
        "runner" => "amp",
    ]);

    $promise = $driver
        ->html($sample)
        ->size("A4")
        ->orientation("portrait")
        ->dpi(300)
        ->render($runner);

    $results = yield $promise;

    print "done" . PHP_EOL;
    Loop::stop();
}));
