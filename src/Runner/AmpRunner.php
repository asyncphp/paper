<?php

namespace AsyncPHP\Paper\Runner;

use Amp\Parallel\Forking\Fork;
use Amp\Parallel\Threading\Thread;
use AsyncInterop\Promise as AsyncInteropPromise;
use AsyncPHP\Paper\Promise;
use AsyncPHP\Paper\Promise\AmpPromise;
use AsyncPHP\Paper\Runner;
use Closure;

class AmpRunner implements Runner
{
    /**
     * @inheritdoc
     *
     * @param Closure $deferred
     *
     * @return null|AsyncInteropPromise
     */
    public function run(Closure $deferred)
    {
        if (Fork::supported()) {
           return Fork::spawn($deferred)->join();
       }

       if (Thread::supported()) {
           return Thread::spawn($deferred)->join();
       }

       return null;
    }
}
