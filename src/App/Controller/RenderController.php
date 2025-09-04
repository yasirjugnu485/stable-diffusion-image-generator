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

class RenderController
{
    /**
     * Prepare base params
     *
     * @return array
     */
    private function prepareParams(): array
    {
        $navbarController = new NavbarController();
        $navbar = $navbarController->getData();
        $successController = new SuccessController();
        $success = $successController->getSuccess();
        $errorController = new ErrorController();
        $error = $errorController->getError();
        return [
            'navbar' => $navbar,
            'success' => $success,
            'error' => $error,
        ];
    }

    /**
     * Render by type
     *
     * @param string $type Type
     * @return void
     */
    public function renderByType(string $type): void
    {
        $params = $this->prepareParams();

        $fileController = new FileController();

        $params['data'] = $fileController->collectFilesByType($type);
        $params['breadcrumbs'] = [
            [
                'title' => $type,
                'url' => '/' . $type,
                'active' => false
            ]
        ];
        $params['template'] = 'images_base.php';

        $this->render($params);
    }

    /**
     * Render by type and date time
     *
     * @param string $type Type
     * @param string $dateTime Date time
     * @return void
     */
    public function renderByTypeAndDateTime(string $type, string $dateTime): void
    {
        $params = $this->prepareParams();

        $fileController = new FileController();

        $dateTimeName = str_replace(':', '-', $dateTime);
        $dateTimeName = str_replace(' ', '_', $dateTimeName);
        $params['data'] = $fileController->collectFilesByTypeAndDateTime($type, $dateTime);
        $params['breadcrumbs'] = [
            [
                'title' => $type,
                'url' => '/' . $type,
                'active' => false
            ],
            [
                'title' => $dateTime,
                'url' => '/' . $type . '/' . $dateTimeName,
                'active' => true
            ]
        ];
        $params['template'] = 'images_base.php';

        $this->render($params);
    }

    /**
     * Render sets of checkpoints
     *
     * @return void
     */
    public function renderCheckpoints(): void
    {
        $params = $this->prepareParams();

        $fileController = new FileController();
        $params['checkpoints'] = $fileController->collectUsedCheckpoints();
        $params['data'] = $fileController->collectCheckpointFiles();
        $params['base_breadcrumbs'] = [
            [
                'title' => 'checkpoints',
                'url' => '/checkpoints',
                'active' => false
            ]
        ];

        $params['template'] = 'images_checkpoints.php';

        $this->render($params);
    }

    /**
     * Render by checkpoint
     *
     * @param string $checkpoint Checkpoint
     * @return void
     */
    public function renderByCheckpoint(string $checkpoint): void
    {
        $params = $this->prepareParams();

        $fileController = new FileController();

        $params['data'] = $fileController->collectFilesByCheckpoint($checkpoint);
        $params['breadcrumbs'] = [
            [
                'title' => 'checkpoints',
                'url' => '/checkpoints',
                'active' => false
            ],
            [
                'title' => $checkpoint,
                'url' => '/checkpoints/' . $checkpoint,
                'active' => true
            ]
        ];
        $params['template'] = 'images_base.php';

        $this->render($params);
    }

    /**
     * Render prompts
     *
     * @return void
     */
    public function renderPrompts(): void
    {
        $params = $this->prepareParams();
        $promptController = new PromptController();
        $params['prompts'] = $promptController->getPromptDirectories();
        $params['template'] = 'prompts.php';

        $this->render($params);
    }

    /**
     * Render initialize images
     *
     * @return void
     */
    public function renderInitImages(): void
    {
        $params = $this->prepareParams();
        $initImagesController = new InitImageController();
        $params['init_images'] = $initImagesController->getInitImagesDirectories();
        $params['template'] = 'init_images.php';

        $this->render($params);
    }

    /**
     * Render prompt editor
     *
     * @param string $promptDirectory Prompt directory
     * @return void
     */
    public function renderPromptEditor(string $promptDirectory): void
    {
        $params = $this->prepareParams();
        $promptController = new PromptController();
        $promptFiles = $promptController->getPromptFiles($promptDirectory);
        $params['directory'] = $promptDirectory;
        $params['files'] = $promptFiles;
        $params['template'] = 'prompt_editor.php';

        $this->render($params);
    }

    /**
     * Render initialize images editor
     *
     * @param string $initImagesDirectory Initialize images directory
     * @return void
     */
    public function renderInitImagesEditor(string $initImagesDirectory): void
    {
        $params = $this->prepareParams();
        $initImagesController = new InitImageController();
        $initImagesImages = $initImagesController->getInitImagesImages($initImagesDirectory);;
        $params['directory'] = $initImagesDirectory;
        $params['images'] = $initImagesImages;
        $params['template'] = 'init_images_editor.php';

        $this->render($params);
    }

    /**
     * Render
     *
     * @param array $params Parameters
     * @return void
     */
    public function render(array $params = []): void
    {
        ob_start();
        foreach ($params as $key => $value) {
            ${$key} = $value;
        }
        include(ROOT_DIR . 'templates/main.php');
        $rendered = ob_get_contents() . "\n";
        ob_end_clean();

        echo $rendered;

        exit();
    }
}