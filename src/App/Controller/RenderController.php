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
        $albumController = new AlbumController();
        $albumData = $albumController->getAlbumData();
        return [
            'navbar' => $navbar,
            'album_data' => $albumData,
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
        $params['type'] = $type;
        $params['date_time'] = $dateTimeName;

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
                'title' => 'Checkpoints',
                'url' => '/checkpoints',
                'active' => false
            ]
        ];
        $params['template'] = 'images_checkpoints.php';

        $this->render($params);
    }

    /**
     * Render album
     *
     * @param array $requestIndex Request index
     * @return void
     */
    public function renderAlbum(array $requestIndex): void
    {
        $params = $this->prepareParams();
        $albumController = new AlbumController();
        $params['sub_directories'] = $albumController->collectAlbumSubDirectories();
        $params['data'] = $albumController->collectAlbumFiles();
        unset($requestIndex[0]);
        unset($requestIndex[0]);
        unset($requestIndex[0]);
        $params['request_index'] = $requestIndex;
        $params['breadcrumbs'] = [];
        $slug = '/';
        foreach ($requestIndex as $index => $value) {
            if ($index === 0) {
                $slug .= 'album' . '/';
                $params['breadcrumbs'][] = [
                    'title' => 'Album',
                    'url' => rtrim($slug, '/'),
                    'active' => false
                ];
            } else {
                $slug .= $value . '/';
                $params['breadcrumbs'][] = [
                    'title' => str_replace('_', ' ', $value),
                    'url' => rtrim($slug, '/'),
                    'active' => false
                ];
            }
        }
        $params['album'] = $slug;
        $params['template'] = 'album.php';

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
     * Render generator
     *
     * @return void
     */
    public function renderGenerator(): void
    {
        $params = $this->prepareParams();
        $generatorController = new GeneratorController();
        if ($generatorController->getStartGeneration()) {
            $params['template'] = 'generate.php';
            $this->render($params);
        }
        $params = array_merge($params, $generatorController->getData());
        $params['template'] = 'generator.php';

        $this->render($params);
    }

    /**
     * Render settings
     *
     * @return void
     */
    public function renderSettings(): void
    {
        $params = $this->prepareParams();
        $settingsController = new SettingsController();
        $params = array_merge($params, $settingsController->getSettings());
        $params['template'] = 'settings.php';

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