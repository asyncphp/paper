<?php

require __DIR__ . "/../vendor/autoload.php";

use AsyncPHP\Paper\Factory;
use React\EventLoop\Factory as EventLoopFactory;

error_reporting(E_ERROR | E_PARSE);

// this too could be async...
$sample = file_get_contents(__DIR__ . "/sample.html");

$factory = new Factory();

$driver = $factory->createDriver([
    "driver" => "webkit",
]);

$runner = $factory->createRunner([
    "runner" => "react",
]);

$loop = EventLoopFactory::create();

// this is a React\ChildProcess\Process...
$process = $driver
    ->html($sample)
    ->size("A4")
    ->orientation("portrait")
    ->dpi(300)
    ->render($runner);

$process->on("exit", function() use ($loop) {
    $loop->stop();
});

$loop->addTimer(0.001, function($timer) use ($process) {
    $process->start($timer->getLoop());

    $process->stdout->on("data", function($output) {
        // this too could be async...
        file_put_contents(__DIR__ . "/async-react-webkit.pdf", $output);
    });
});

$loop->run();
