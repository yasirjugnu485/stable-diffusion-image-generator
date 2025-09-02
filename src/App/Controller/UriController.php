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

        if (($requestIndex[1] === 'txt2img' || $requestIndex[1] === 'img2img' || $requestIndex[1] === 'loop') &&
            !isset($requestIndex[2])) {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectFilesByType($requestIndex[1]);
            if ($files) {
                $imagesController = new RenderController();
                $imagesController->renderByType();
            }
        } elseif (($requestIndex[1] === 'checkpoints') && !isset($requestIndex[2])) {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectCheckpointFiles();
            if ($files) {
                $imagesController = new RenderController();
                $imagesController->renderCheckpoints();
            }
        } elseif ($requestIndex[1] === 'txt2img' || $requestIndex[1] === 'img2img' || $requestIndex[1] === 'loop') {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectFilesByTypeAndDateTime($requestIndex[1], $requestIndex[2]);
            if ($files) {
                $imagesController = new RenderController();
                $imagesController->renderByTypeAndDateTime();
            }
        } elseif ($requestIndex[1] === 'checkpoints' || trim($requestIndex[2])) {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectFilesByCheckpoint(trim($requestIndex[2]));
            if ($files) {
                $imagesController = new RenderController();
                $imagesController->renderByCheckpoint();
            }
        }

        $this->notFound();
    }

    private function home(): void
    {
        new HomeController();
    }

    private function notFound(): void
    {
        new NotFoundController();
    }
}