<?php

namespace AsyncPHP\Paper\Driver;

use Amp;
use AsyncInterop\Loop;
use AsyncPHP\Paper\Decorator;
use AsyncPHP\Paper\Driver;
use AsyncPHP\Paper\Runner;
use AsyncPHP\Paper\Runner\AmpRunner;
use AsyncPHP\Paper\Runner\ReactRunner;
use React\EventLoop\Factory;

final class SyncDriver implements Decorator, Driver
{
    /**
     * @var Driver
     */
    private $decorated;

    /**
     * @param Driver $decorated
     */
    public function __construct(Driver $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @inheritdoc
     *
     * @param null|string $header
     *
     * @return string|static
     */
    public function header($header = null)
    {
        return $this->access("header", $header);
    }

    /**
     * Works as a universal getter and setter.
     *
     * @param string $key
     * @param mixed $value
     */
    private function access($key, $value = null)
    {
        if (is_null($value)) {
            return $this->decorated->$key();
        }

        $this->decorated->$key($value);
        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param null|string $body
     *
     * @return string|static
     */
    public function body($body = null)
    {
        return $this->access("body", $body);
    }

    /**
     * @inheritdoc
     *
     * @param null|string $footer
     *
     * @return string|static
     */
    public function footer($footer = null)
    {
        return $this->access("footer", $footer);
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
        return $this->access("size", $size);
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
        return $this->access("orientation", $orientation);
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
        return $this->access("dpi", $dpi);
    }

    /**
     * @inheritdoc
     *
     * @param Runner $runner
     *
     * @return null|string
     */
    public function render(Runner $runner)
    {
        if ($runner instanceof AmpRunner) {
            return $this->renderWithAmp($runner);
        }

        if ($runner instanceof ReactRunner) {
            return $this->renderWithReact($runner);
        }

        return null;
    }

    /**
     * Run the render step using Amp classes.
     *
     * @param Runner $runner
     *
     * @return null|string
     */
    private function renderWithAmp(Runner $runner)
    {
        $result = null;

        Loop::execute(Amp\wrap(function() use (&$result, &$runner) {
            $result = yield $this->decorated->render($runner);
        }));

        return $result;
    }

    /**
     * Run the render step using React classes.
     *
     * @param Runner $runner
     *
     * @return null|string
     */
    private function renderWithReact(Runner $runner)
    {
        $result = null;

        $loop = Factory::create();
        $process = $this->decorated->render($runner);

        $process->on("exit", function() use ($loop) {
            $loop->stop();
        });

        $loop->addTimer(0.001, function($timer) use ($process, &$result) {
            $process->start($timer->getLoop());

            $process->stdout->on("data", function($output) use (&$result) {
                $result = $output;
            });
        });

        $loop->run();

        return $result;
    }

    /**
     * @inheritdoc
     *
     * @return Driver
     */
    public function decorated()
    {
        return $this->decorated;
    }
}
