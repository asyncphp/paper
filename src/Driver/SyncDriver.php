<?php

namespace AsyncPHP\Paper\Driver;

use Amp;
use AsyncInterop\Loop;
use AsyncPHP\Paper\Decorator;
use AsyncPHP\Paper\Driver;

final class SyncDriver implements Decorator, Driver
{
    /**
     * @var Driver
     */
    private $decorated;

    /**
     * @inheritdoc
     */
    public function __construct(Driver $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @inheritdoc
     */
    public function html(string $html = null)
    {
        if (is_null($html)) {
            return $this->decorated->html();
        }

        $this->decorated->html($html);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function size(string $size = null)
    {
        if (is_null($size)) {
            return $this->decorated->size();
        }

        $this->decorated->size($size);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function orientation(string $orientation = null)
    {
        if (is_null($orientation)) {
            return $this->decorated->orientation();
        }

        $this->decorated->orientation($orientation);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function dpi(int $dpi = null)
    {
        if (is_null($dpi)) {
            return $this->decorated->dpi();
        }

        $this->decorated->dpi($dpi);
        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function render()
    {
        $result = null;

        Loop::execute(Amp\wrap(function() use (&$result) {
            $result = yield $this->decorated->render();
        }));

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function decorated(): Driver
    {
        return $this->decorated;
    }
}
