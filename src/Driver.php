<?php

namespace AsyncPHP\Paper;

use AsyncInterop\Promise;

interface Driver
{
    public function html(string $html = null);

    public function size(string $size = null);

    public function orientation(string $orientation = null);

    public function dpi(int $dpi = null);

    public function render();
}
