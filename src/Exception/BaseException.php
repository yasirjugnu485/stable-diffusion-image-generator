<?php

declare(strict_types=1);

namespace Exception;

use Controller\LogController;
use Controller\ModelController;
use Exception;
use Throwable;

class BaseException extends Exception
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        new LogController('ERROR: ' . $message);

        try {
            $modelController = new ModelController();
            $modelController->restoreStartModel();
        } catch (Exception $exception) {}
    }
}