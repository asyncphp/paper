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
     * @param null|string $html
     *
     * @return string|static
     */
    public function html($html = null)
    {
        if (is_null($html)) {
            return $this->decorated->html();
        }

        $this->decorated->html($html);
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
            return $this->decorated->size();
        }

        $this->decorated->size($size);
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
            return $this->decorated->orientation();
        }

        $this->decorated->orientation($orientation);
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
            return $this->decorated->dpi();
        }

        $this->decorated->dpi($dpi);
        return $this;
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
