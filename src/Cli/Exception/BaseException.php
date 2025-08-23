<?php

declare(strict_types=1);

namespace Cli\Exception;

use Cli\Controller\LogController;
use Exception;
use Throwable;

class BaseException extends Exception
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        new LogController('ERROR: ' . $message);
    }
}