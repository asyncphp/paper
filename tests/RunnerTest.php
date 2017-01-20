<?php

namespace AsyncPHP\Paper\Tests;

use AsyncPHP\Paper\Runner\AmpRunner;
use AsyncPHP\Paper\Runner\ReactRunner;
use AsyncPHP\Paper\Driver\DomDriver;
use AsyncPHP\Paper\Driver\SyncDriver;
use PHPUnit\Framework\TestCase;

class RUnnerTest extends TestCase
{
    public function testDomDriver()
    {
        error_reporting(E_ERROR | E_PARSE);

        $sample = file_get_contents(__DIR__ . "/fixtures/sample.html");

        $driver = new SyncDriver(new DomDriver());

        $ampRunner = new AmpRunner();
        $reactRunner = new ReactRunner();

        $ampResult = $driver
            ->body($sample)
            ->size("A4")
            ->orientation("portrait")
            ->dpi(300)
            ->render($ampRunner);

        file_put_contents(__DIR__ . "/test-amp.pdf", $ampResult);

        $reactResult = $driver
            ->body($sample)
            ->size("A4")
            ->orientation("portrait")
            ->dpi(300)
            ->render($reactRunner);

        file_put_contents(__DIR__ . "/test-react.pdf", $reactResult);

        exec("diff-pdf -v " . __DIR__ . "/test-amp.pdf " . __DIR__ . "/test-react.pdf", $output);

        foreach ($output as $line) {
            if (stristr($line, "differs")) {
                $this->fail();
            }
        }

        unlink(__DIR__ . "/test-amp.pdf");
        unlink(__DIR__ . "/test-react.pdf");
    }
}
