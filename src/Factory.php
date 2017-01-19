<?php

namespace AsyncPHP\Paper;

use AsyncPHP\Paper\Driver;
use AsyncPHP\Paper\Driver\DomDriver;
use AsyncPHP\Paper\Driver\PrinceDriver;
use AsyncPHP\Paper\Driver\SyncDriver;
use AsyncPHP\Paper\Driver\WebkitDriver;
use AsyncPHP\Paper\Runner;
use AsyncPHP\Paper\Runner\AmpRunner;
use AsyncPHP\Paper\Runner\ReactRunner;

class Factory
{
    /**
     * Creates a driver instance, based on provided config options.
     *
     * @param array $options
     *
     * @return null|Driver
     */
    public function createDriver(array $options = ["driver" => "dom"])
    {
        $driver = null;

        if ($options["driver"] === "dom") {
            $driver = new DomDriver(
                !empty($options["dom"]["options"]) ? $options["dom"]["options"] : []
            );
        }

        if ($options["driver"] === "prince") {
            $driver = new PrinceDriver(
                !empty($options["prince"]["binary"]) ? $options["prince"]["binary"] : "prince",
                !empty($options["prince"]["tempPath"]) ? $options["prince"]["tempPath"] : __DIR__,
                !empty($options["prince"]["options"]) ? $options["prince"]["options"] : []
            );
        }

        if ($options["driver"] === "webkit") {
            $driver = new WebkitDriver(
                !empty($options["webkit"]["binary"]) ? $options["webkit"]["binary"] : "wkhtmltopdf",
                !empty($options["webkit"]["tempPath"]) ? $options["webkit"]["tempPath"] : __DIR__,
                !empty($options["webkit"]["options"]) ? $options["webkit"]["options"] : []
            );
        }

        if (empty($options["sync"])) {
            return $driver;
        }

        if ($driver !== null) {
            return new SyncDriver($driver);
        }

        return null;
    }

    /**
     * Creates a runner instance, based on provided config options.
     *
     * @param array $options
     *
     * @return null|Runner
     */
    public function createRunner(array $options = ["runner" => "amp"])
    {
        $runner = null;

        if ($options["runner"] === "amp") {
            $runner = new AmpRunner();
        }

        if ($options["runner"] === "react") {
            $runner = new ReactRunner();
        }

        return $runner;
    }
}
