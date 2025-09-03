<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

echo 'Try starting build in webserver' . PHP_EOL;
try {
    exec('php -S 0.0.0.0:8000 -t public/ > /dev/null 2>&1 &');
    echo 'Successfully started build in webserver on "http://localhost:8000"' . PHP_EOL;
} catch (Throwable $throwable) {
    echo 'Error on starting build in webserver' . PHP_EOL;
}