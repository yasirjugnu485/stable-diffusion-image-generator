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

class RedirectController
{
    /**
     * Constructor
     *
     * @param string|null $url URL
     * @return void
     */
    public function __construct(string|null $url = null)
    {
        if ($url) {
            $httpReferer = $_SERVER['HTTP_REFERER'] ?? 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            if (str_starts_with($httpReferer, 'http://')) {
                $httpReferer = str_replace('http://', '', $httpReferer);
                $split = explode('/', $httpReferer);
                $url = 'http://' . $split[0] . $url;
            } elseif (str_starts_with($httpReferer, 'https://')) {
                $httpReferer = str_replace('https://', '', $httpReferer);
                $split = explode('/', $httpReferer);
                $url = 'https://' . $split[0] . $url;
            }
        } else {
            $url = $_SERVER['HTTP_REFERER'] ?? 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        header('Location: ' . $url);
        exit();
    }
}