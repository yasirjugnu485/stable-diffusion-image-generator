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

        $requestIndex = explode('/', rtrim($requestUri, '/'));
        if (count($requestIndex) > 3) {
            $this->notFound();
        }

        if (($requestIndex[1] === 'txt2img' || $requestIndex[1] === 'img2img' || $requestIndex[1] === 'loop') &&
            !isset($requestIndex[2])) {
            $fileController = new FileController();
            $files = $fileController->collectFilesByType($requestIndex[1]);
            if ($files) {
                $renderController = new RenderController();
                $renderController->renderByType();
            }
        } elseif (($requestIndex[1] === 'txt2img' || $requestIndex[1] === 'img2img' || $requestIndex[1] === 'loop') &&
            isset($requestIndex[2])) {
            $fileController = new FileController();
            $files = $fileController->collectFilesByTypeAndDateTime($requestIndex[1], $requestIndex[2]);
            if ($files) {
                $renderController = new RenderController();
                $renderController->renderByTypeAndDateTime();
            }
        } elseif ($requestIndex[1] === 'checkpoints' && !isset($requestIndex[2])) {
            $fileController = new FileController();
            $files = $fileController->collectCheckpointFiles();
            if ($files) {
                $renderController = new RenderController();
                $renderController->renderCheckpoints();
            }
        } elseif ($requestIndex[1] === 'checkpoints' && isset($requestIndex[2])) {
            $fileController = new FileController();
            $files = $fileController->collectFilesByCheckpoint($requestIndex[2]);
            if ($files) {
                $renderController = new RenderController();
                $renderController->renderByCheckpoint();
            }
        } elseif ($requestIndex[1] === 'prompt-merger' && !isset($requestIndex[2])) {
            $promptController = new PromptController();
            $prompts = $promptController->getPromptDirectories();
            if ($prompts) {
                $renderController = new RenderController();
                $renderController->renderPrompts();
            }
        } elseif ($requestIndex[1] === 'prompt-merger' && isset($requestIndex[2])) {
            $promptController = new PromptController();
            if ($promptController->promptDirectoryExists($requestIndex[2])) {
                $renderController = new RenderController();
                $renderController->renderPromptEditor($requestIndex[2]);
            }
        } elseif ($requestIndex[1] === 'initialize-images' && !isset($requestIndex[2])) {
            $initImagesController = new InitImagesController();
            $initImages = $initImagesController->getInitImagesDirectories();
            if ($initImages) {
                $renderController = new RenderController();
                $renderController->renderInitImages();
            }
        } elseif ($requestIndex[1] === 'initialize-images' && isset($requestIndex[2])) {
            $initImagesController = new InitImagesController();
            if ($initImagesController->initImagesDirectoryExists($requestIndex[2])) {
                $renderController = new RenderController();
                $renderController->renderInitImagesEditor($requestIndex[2]);
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