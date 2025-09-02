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

class UriController
{
    /**
     * Constructor
     *
     * @return void
     */
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
                $renderController = new RenderController();
                $renderController->renderByType();
            }
        } elseif (($requestIndex[1] === 'txt2img' || $requestIndex[1] === 'img2img' || $requestIndex[1] === 'loop') &&
            isset($requestIndex[2])) {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectFilesByTypeAndDateTime($requestIndex[1], $requestIndex[2]);
            if ($files) {
                $renderController = new RenderController();
                $renderController->renderByTypeAndDateTime();
            }
        } elseif ($requestIndex[1] === 'checkpoints' && !isset($requestIndex[2])) {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectCheckpointFiles();
            if ($files) {
                $renderController = new RenderController();
                $renderController->renderCheckpoints();
            }
        } elseif ($requestIndex[1] === 'checkpoints' || isset($requestIndex[2])) {
            $fileCollectorController = new FileCollectorController();
            $files = $fileCollectorController->collectFilesByCheckpoint($requestIndex[2]);
            if ($files) {
                $renderController = new RenderController();
                $renderController->renderByCheckpoint();
            }
        } elseif ($requestIndex[1] === 'prompt-merger' && !isset($requestIndex[2])) {
            $promptCollectorController = new PromptCollectorController();
            $prompts = $promptCollectorController->getPrompts();
            if ($prompts) {
                $renderController = new RenderController();
                $renderController->renderPrompts();
            }
        } elseif ($requestIndex[1] === 'initialize-images' && !isset($requestIndex[2])) {
            $initImagesCollectorController = new InitImagesCollectorController();
            $initImages = $initImagesCollectorController->getInitImages();
            if ($initImages) {
                $renderController = new RenderController();
                $renderController->renderInitImages();
            }
        }

        $this->notFound();
    }

    /**
     * Render home
     *
     * @return void
     */
    private function home(): void
    {
        new HomeController();
    }

    /**
     * Render not found
     *
     * @return void
     */
    private function notFound(): void
    {
        new NotFoundController();
    }
}