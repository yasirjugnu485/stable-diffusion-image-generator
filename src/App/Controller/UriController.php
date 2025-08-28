<?php

declare(strict_types=1);

namespace App\Controller;

use Cli\Controller\PayloadController;

class UriController
{
    public function __construct()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        if ($requestUri === '' || $requestUri === '/') {
            $this->home();
        }

        $requestIndex = explode('/', $requestUri);
        if (count($requestIndex) !== 3) {
            $this->notFound();
        }

        if ($requestIndex[1] === 'txt2img' || $requestIndex[1] === 'img2img' || $requestIndex[1] === 'loop') {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectFilesByTypeAndDateTime($requestIndex[1], $requestIndex[2]);
            if (!$files) {
                $this->notFound();
            }
        }

        $this->images();
    }

    private function home(): void
    {
        new HomeController();
    }

    private function images(): void
    {
        new ImagesController();
    }

    private function notFound(): void
    {
        new NotFoundController();
    }
}