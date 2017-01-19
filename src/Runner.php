<?php

namespace AsyncPHP\Paper;

use Closure;

interface Runner
{
    /**
     * Runs a deferred callback in a parallel process/thread, returning the
     * equivalent Amp or React eventual value.
     *
     * @param Closure $deferred
     *
     * @return mixed
     */
    public function run(Closure $deferred);
}
