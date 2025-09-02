<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace Cli\Controller;

class EchoController
{
    /**
     * Constructor
     *
     * @param string $message Message
     */
    public function __construct(string $message = '')
    {
        $logController = new LogController($message);

        echo $message . PHP_EOL;
    }
}