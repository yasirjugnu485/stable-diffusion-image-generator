<?php

declare(strict_types=1);

namespace Cli\Exception;

use Throwable;

class StableDiffusionServiceException extends BaseException
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        echo 'ERROR: ' . $message . PHP_EOL;

        exit($code);
    }
}