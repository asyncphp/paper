<?php

namespace AsyncPHP\Paper;

use AsyncPHP\Paper\Driver\DomDriver;
use AsyncPHP\Paper\Driver\PrinceDriver;
use AsyncPHP\Paper\Driver\SyncDriver;
use AsyncPHP\Paper\Driver\WebkitDriver;
use InvalidArgumentException;

class Factory
{
    public function createDriver(array $options = ["driver" => "dom"])
    {
        if (empty($options["driver"]) || !in_array($options["driver"], ["dom", "prince", "webkit"])) {
            throw new InvalidArgumentException("Unrecognised driver");
        }

        if ($options["driver"] === "dom") {
            $driver = new DomDriver(
                $options["dom"]["options"] ?? []
            );
        }

        if ($options["driver"] === "prince") {
            $driver = new PrinceDriver(
                $options["prince"]["binary"] ?? "prince",
                $options["prince"]["tempPath"] ?? __DIR__,
                $options["prince"]["options"] ?? []
            );
        }

        if ($options["driver"] === "webkit") {
            $driver = new WebkitDriver(
                $options["webkit"]["binary"] ?? "wkhtmltopdf",
                $options["webkit"]["tempPath"] ?? __DIR__,
                $options["webkit"]["options"] ?? []
            );
        }

        if (empty($options["sync"])) {
            return $driver;
        }

        return new SyncDriver($driver);
    }
}
