<?php

namespace AsyncPHP\Paper\Tests;

use AsyncPHP\Paper\Runner\AmpRunner;
use AsyncPHP\Paper\Driver\WebkitDriver;
use AsyncPHP\Paper\Driver\SyncDriver;
use PHPUnit\Framework\TestCase;

class WebkitDriverTest extends TestCase
{
    public function testWebkitDriver()
    {
        $which = `which wkhtmltopdf`;

        if (empty($which) || $which === "wkhtmltopdf not found") {
            $this->markTestSkipped("
It appears you don't have the wkhtmltopdf installed or in your path. Better to
skip the test, in that case.
            ");
        }

        error_reporting(E_ERROR | E_PARSE);

        $driver = new SyncDriver(new WebkitDriver($binary = "/usr/local/bin/wkhtmltopdf", $temp = __DIR__));

        $runner = new AmpRunner();

        $result = $driver
            ->body(file_get_contents(__DIR__ . "/fixtures/sample.html"))
            ->size("A4")
            ->orientation("portrait")
            ->dpi(300)
            ->render($runner);

        file_put_contents(__DIR__ . "/test-webkit.pdf", $result);

        exec("diff-pdf -v " . __DIR__ . "/test-webkit.pdf " . __DIR__ . "/fixtures/webkit.pdf", $output);

        foreach ($output as $line) {
            if (stristr($line, "differs")) {
                $this->fail();
            }
        }

        unlink(__DIR__ . "/test-webkit.pdf");
    }

    /**
     * Remove unique dates from PDF content, so it can be compared.
     */
    private function scrubDates($content): string
    {
        $content = preg_replace("/\/CreationDate[^\\n]+/", "", $content);
        $content = preg_replace("/\/ModDate[^\\n]+/", "", $content);

        return $content;
    }
}
