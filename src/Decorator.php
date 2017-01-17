<?php

namespace AsyncPHP\Paper;

use AsyncPHP\Paper\Driver;

interface Decorator
{
    public function decorated(): Driver;
}
