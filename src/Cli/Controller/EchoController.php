<?php

declare(strict_types=1);

namespace Cli\Controller;

class EchoController
{
    public function __construct(string $message = '')
    {
        $logController = new LogController($message);

        echo $message . PHP_EOL;
    }
}