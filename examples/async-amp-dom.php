<?php

require __DIR__ . "/../vendor/autoload.php";

use AsyncInterop\Loop;
use AsyncPHP\Paper\Factory;

error_reporting(E_ERROR | E_PARSE);

// this too could be async...
$sample = file_get_contents(__DIR__ . "/sample.html");

Loop::execute(Amp\wrap(function() use ($sample) {
    $factory = new Factory();

    $driver = $factory->createDriver([
        "driver" => "dom",
    ]);

    $runner = $factory->createRunner([
        "runner" => "amp",
    ]);

    // this is an AsyncInterop\Promise...
    $promise = $driver
        ->body($sample)
        ->size("A4")
        ->orientation("portrait")
        ->dpi(300)
        ->render($runner);

    $results = yield $promise;

    // this too could be async...
    file_put_contents(__DIR__ . "/async-amp-dom.pdf", $results);
}));
