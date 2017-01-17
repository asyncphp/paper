<?php

namespace AsyncPHP\Paper\Tests;

use AsyncPHP\Paper\Driver\DomDriver;
use AsyncPHP\Paper\Driver\PrinceDriver;
use AsyncPHP\Paper\Driver\SyncDriver;
use AsyncPHP\Paper\Driver\WebkitDriver;
use AsyncPHP\Paper\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testFactory()
    {
        $factory = new Factory();

        $this->assertInstanceOf(DomDriver::class, $factory->createDriver(["driver" => "dom"]));
        $this->assertInstanceOf(PrinceDriver::class, $factory->createDriver(["driver" => "prince"]));
        $this->assertInstanceOf(WebkitDriver::class, $factory->createDriver(["driver" => "webkit"]));

        $this->assertInstanceOf(SyncDriver::class, $factory->createDriver(["driver" => "dom", "sync" => true]));

        $this->assertInstanceOf(DomDriver::class, $factory->createDriver(["driver" => "dom", "sync" => true])->decorated());
        $this->assertInstanceOf(PrinceDriver::class, $factory->createDriver(["driver" => "prince", "sync" => true])->decorated());
        $this->assertInstanceOf(WebkitDriver::class, $factory->createDriver(["driver" => "webkit", "sync" => true])->decorated());
    }
}
