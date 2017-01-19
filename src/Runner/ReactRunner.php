<?php

namespace AsyncPHP\Paper\Runner;

use AsyncPHP\Paper\Runner;
use Closure;
use React\ChildProcess\Process;
use SuperClosure\Serializer;

class ReactRunner implements Runner
{
    /**
     * @inheritdoc
     *
     * @param Closure $deferred
     *
     * @return Process
     */
    public function run(Closure $deferred)
    {
        $autoload = $this->autoload();

        $serializer = new Serializer();
        $serialized = base64_encode($serializer->serialize($deferred));

        $raw = "
            require_once '{$autoload}';

            \$serializer = new SuperClosure\Serializer();
            \$serialized = base64_decode('{$serialized}');

            return call_user_func(
                \$serializer->unserialize(\$serialized)
            );
        ";

        $encoded = addslashes(base64_encode($raw));

        return new Process("exec php -r 'print eval(base64_decode(\"{$encoded}\"));'");
    }

    /**
     * Return the path to the class autoloader.
     *
     * @return string
     */
    private function autoload()
    {
        if (file_exists(__DIR__ . "/../../vendor/autoload.php")) {
            return realpath(__DIR__ . "/../../vendor/autoload.php");
        }

        if (file_exists(__DIR__ . "/../../../../autoload.php")) {
            return realpath(__DIR__ . "/../../../../autoload.php");
        }
    }
}
