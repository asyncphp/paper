<?php

namespace AsyncPHP\Paper\Driver;

use Amp\Parallel\Forking\Fork;
use Amp\Parallel\Threading\Thread;
use AsyncInterop\Promise;
use AsyncPHP\Paper\Driver;
use Exception;
use StdClass;

abstract class BaseDriver implements Driver
{
    /**
     * @var string
     */
    protected $html;

    /**
     * @var string
     */
    protected $size = "A4";

    /**
     * @var string
     */
    protected $orientation = "portrait";

    /**
     * @var string
     */
    protected $dpi = 300;

    /**
     * @inheritdoc
     */
    public function html(string $html = null)
    {
        if (is_null($html)) {
            return $this->html;
        }

        $this->html = $html;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function size(string $size = null)
    {
        if (is_null($size)) {
            return $this->size;
        }

        $this->size = $size;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function orientation(string $orientation = null)
    {
        if (is_null($orientation)) {
            return $this->orientation;
        }

        $this->orientation = $orientation;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function dpi(int $dpi = null)
    {
        if (is_null($dpi)) {
            return $this->dpi;
        }

        $this->dpi = $dpi;

        return $this;
    }

    /**
     * Pick the first available extension, and run the function in parallel.
     */
    protected function parallel(callable $function): Promise
    {
        if (Fork::supported()) {
            return Fork::spawn($function)->join();
        }

        if (Thread::supported()) {
            return Thread::spawn($function)->join();
        }

        throw new Exception();
    }

    /**
     * Get context variables for parallel execution.
     */
    protected function data(): StdClass
    {
        return json_decode(json_encode([
            "html" => $this->html,
            "size" => $this->size,
            "orientation" => $this->orientation,
            "dpi" => $this->dpi,
        ]));
    }
}
