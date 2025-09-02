<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace Cli\Exception;

use Cli\Controller\LogController;
use Exception;
use Throwable;

class BaseException extends Exception
{
    /**
     * Constructor
     *
     * @param string $message Error message
     * @param int $code Error code
     * @param Throwable|null $previous Previous exception
     * @return void
     * @throws PromptImageGeneratorException
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        new LogController('ERROR: ' . $message);
    }
}