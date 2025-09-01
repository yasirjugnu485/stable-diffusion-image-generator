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
        if (count($requestIndex) > 3) {
            $this->notFound();
        }

        if ($requestIndex[1] === 'txt2img' && !isset($requestIndex[2])) {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectFilesByType('txt2img');
            if ($files) {
                $imagesController = new imagesController();
                $imagesController->renderByType();
            }
        } elseif ($requestIndex[1] === 'img2img' && !isset($requestIndex[2])) {
            $fileCollectorController = new FileCollectorController();

        } elseif ($requestIndex[1] === 'loop' && !isset($requestIndex[2])) {
            $fileCollectorController = new FileCollectorController();

        } elseif ($requestIndex[1] === 'txt2img' || $requestIndex[1] === 'img2img' || $requestIndex[1] === 'loop') {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectFilesByTypeAndDateTime($requestIndex[1], $requestIndex[2]);
            if ($files) {
                $imagesController = new imagesController();
                $imagesController->renderByTypeAndDateTime();
            }
        } elseif ($requestIndex[1] === 'checkpoints' || trim($requestIndex[2])) {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectFilesByCheckpoint($requestIndex[2]);
            if ($files) {
                $imagesController = new imagesController();
                $imagesController->renderByCheckpoint();
            }
        }

        $this->notFound();
    }

    private function home(): void
    {
        new HomeController();
    }

    private function images(): void
    {
        new imagesController();
    }

    private function notFound(): void
    {
        new NotFoundController();
    }
}