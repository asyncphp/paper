<?php

require __DIR__ . "/../vendor/autoload.php";

use AsyncInterop\Loop;
use AsyncPHP\Paper\Factory;

error_reporting(E_ERROR | E_PARSE);

$sample = file_get_contents(__DIR__ . "/sample.html");

$factory = new Factory();

$driver = $factory->createDriver([
    "sync" => true,
    "driver" => "webkit",
]);

$runner = $factory->createRunner([
    "runner" => "react",
]);

$results = $driver
    ->html($sample)
    ->size("A4")
    ->orientation("portrait")
    ->dpi(300)
    ->render($runner);

file_put_contents(__DIR__ . "/sync-react-webkit.pdf", $results);
