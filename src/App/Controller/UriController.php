<?php

declare(strict_types=1);

namespace App\Controller;

class UriController
{
    public function __construct()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        if ($requestUri === '' || $requestUri === '/') {
            $this->home();
        }
    }

    private function home(): void
    {
        new HomeController();
    }
}