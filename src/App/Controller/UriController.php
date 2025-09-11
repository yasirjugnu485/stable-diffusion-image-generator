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
        $renderController = new RenderController();
        $promptController = new PromptController();
        $initImagesController = new InitImageController();

        $requestUri = $_SERVER['REQUEST_URI'];
        $requestIndex = explode('/', rtrim($requestUri, '/'));

        if ($requestUri === '' || $requestUri === '/') {
            $renderController->renderHome();
        } elseif (($requestIndex[1] === 'txt2img' || $requestIndex[1] === 'img2img' || $requestIndex[1] === 'loop') &&
            !isset($requestIndex[2])) {
            $renderController->renderImagesByType($requestIndex[1]);
        } elseif (($requestIndex[1] === 'txt2img' || $requestIndex[1] === 'img2img' || $requestIndex[1] === 'loop') &&
            isset($requestIndex[2])) {
            $renderController->renderImagesByTypeAndDateTime($requestIndex[1], $requestIndex[2]);
        } elseif ($requestIndex[1] === 'checkpoints' && !isset($requestIndex[2])) {
            $renderController->renderImagesByCheckpoints();
        } elseif ($requestIndex[1] === 'checkpoints' && isset($requestIndex[2])) {
            $renderController->renderImagesByCheckpoint($requestIndex[2]);
        } elseif ($requestIndex[1] === 'album') {
            $renderController->renderAlbum($requestIndex);
        } elseif ($requestIndex[1] === 'prompt-merger' && !isset($requestIndex[2])) {
            $prompts = $promptController->getPromptDirectories();
            if ($prompts) {
                $renderController->renderPrompts();
            }
        } elseif ($requestIndex[1] === 'prompt-merger' && isset($requestIndex[2])) {
            if ($promptController->promptDirectoryExists($requestIndex[2])) {
                $renderController->renderPromptEditor($requestIndex[2]);
            }
        } elseif ($requestIndex[1] === 'initialize-images' && !isset($requestIndex[2])) {
            $initImages = $initImagesController->getInitImagesDirectories();
            if ($initImages) {
                $renderController->renderInitImages();
            }
        } elseif ($requestIndex[1] === 'initialize-images' && isset($requestIndex[2])) {
            if ($initImagesController->initImagesDirectoryExists($requestIndex[2])) {
                $renderController->renderInitImagesEditor($requestIndex[2]);
            }
        } elseif ($requestIndex[1] === 'generator' && !isset($requestIndex[2])) {
            $renderController->renderGenerator();
        } elseif ($requestIndex[1] === 'generate' && !isset($requestIndex[2])) {
            echo 'php ' . ROOT_DIR . 'run.php --config ' . ROOT_DIR . 'config.app.php';
            shell_exec('php ' . ROOT_DIR . 'run.php --config ' .
                ROOT_DIR . 'config.app.php > /dev/null 2>/dev/null &');
            exit();
        } elseif ($requestIndex[1] === 'settings' && !isset($requestIndex[2])) {
            $renderController->renderSettings();
        }

        $renderController->renderNotFound();
    }
}