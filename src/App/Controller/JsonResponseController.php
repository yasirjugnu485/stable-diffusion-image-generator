<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace App\Controller;

class JsonResponseController
{
    /**
     * Constructor
     *
     * @param array|string|null $data JSON or array response data
     * @return void
     */
    public function __construct(array|string|null $data = null)
    {
        header('Content-Type: application/json; charset=utf-8');
        if ($data === null) {
            echo '{}';
            exit();
        }

        echo is_string($data) ? $data : json_encode($data);
        exit();
    }
}