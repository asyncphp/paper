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
     * @var int
     */
    protected $dpi = 300;

    /**
     * @inheritdoc
     *
     * @param null|string $html
     *
     * @return string|static
     */
    public function html($html = null)
    {
        if (is_null($html)) {
            return $this->html;
        }

        $this->html = $html;
        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param null|string $size
     *
     * @return string|static
     */
    public function size($size = null)
    {
        if (is_null($size)) {
            return $this->size;
        }

        $this->size = $size;
        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param null|string $orientation
     *
     * @return string|static
     */
    public function orientation($orientation = null)
    {
        if (is_null($orientation)) {
            return $this->orientation;
        }

        $this->orientation = $orientation;
        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param null|int $dpi
     *
     * @return int|static
     */
    public function dpi($dpi = null)
    {
        if (is_null($dpi)) {
            return $this->dpi;
        }

        $this->dpi = $dpi;
        return $this;
    }

    /**
     * Get context variables for parallel execution.
     *
     * @return StdClass
     */
    protected function data()
    {
        return json_decode(json_encode([
            "html" => $this->html,
            "size" => $this->size,
            "orientation" => $this->orientation,
            "dpi" => $this->dpi,
        ]));
    }
}
