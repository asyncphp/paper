<?php

namespace AsyncPHP\Paper;

use AsyncPHP\Paper\Driver;

interface Decorator
{
    /**
     * Returns the decorated driver instance.
     *
     * @return Driver
     */
    public function decorated();
}
