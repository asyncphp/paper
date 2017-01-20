<?php

namespace AsyncPHP\Paper\Tests;

use AsyncPHP\Paper\Runner\AmpRunner;
use AsyncPHP\Paper\Driver\PrinceDriver;
use AsyncPHP\Paper\Driver\SyncDriver;
use PHPUnit\Framework\TestCase;

class PrinceDriverTest extends TestCase
{
    public function testPrinceDriver()
    {
        $which = `which prince`;

        if (empty($which) || $which === "prince not found") {
            $this->markTestSkipped("
It appears you don't have the prince installed or in your path. Better to
skip the test, in that case.
            ");
        }

        error_reporting(E_ERROR | E_PARSE);

        $driver = new SyncDriver(new PrinceDriver($binary = "/opt/prince/bin/prince", $temp = __DIR__));

        $runner = new AmpRunner();

        $result = $driver
            ->header("this is the header")
            ->body(file_get_contents(__DIR__ . "/fixtures/sample.html"))
            ->footer("this is the footer")
            ->size("A4")
            ->orientation("portrait")
            ->dpi(300)
            ->render($runner);

        file_put_contents(__DIR__ . "/test-prince.pdf", $result);

        exec("diff-pdf -v " . __DIR__ . "/test-prince.pdf " . __DIR__ . "/fixtures/test-prince.pdf", $output);

        foreach ($output as $line) {
            if (stristr($line, "differs")) {
                $this->fail();
            }
        }

        unlink(__DIR__ . "/test-prince.pdf");
    }
}
