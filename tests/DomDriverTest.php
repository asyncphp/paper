<?php

namespace AsyncPHP\Paper\Tests;

use AsyncPHP\Paper\Runner\AmpRunner;
use AsyncPHP\Paper\Driver\DomDriver;
use AsyncPHP\Paper\Driver\SyncDriver;
use PHPUnit\Framework\TestCase;

class DomDriverTest extends TestCase
{
    public function testDomDriver()
    {
        error_reporting(E_ERROR | E_PARSE);

        $driver = new SyncDriver(new DomDriver());

        $runner = new AmpRunner();

        $result = $driver
            ->body(file_get_contents(__DIR__ . "/fixtures/sample.html"))
            ->size("A4")
            ->orientation("portrait")
            ->dpi(300)
            ->render($runner);

        file_put_contents(__DIR__ . "/test-dom.pdf", $result);

        exec("diff-pdf -v " . __DIR__ . "/test-dom.pdf " . __DIR__ . "/fixtures/dom.pdf", $output);

        foreach ($output as $line) {
            if (stristr($line, "differs")) {
                $this->fail();
            }
        }

        unlink(__DIR__ . "/test-dom.pdf");
    }
}
