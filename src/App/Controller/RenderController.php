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
     * Render home
     *
     * @return void
     */
    public function renderHome(): void
    {
        $params = $this->prepareParams();
        $fileController = new FileController();
        $params['images'] = $fileController->getLastGeneratedImages();
        $params['used_modes'] = $fileController->getUsedModes();
        $params['used_checkpoints'] = $fileController->getCheckpoints();
        $albumController = new AlbumController();
        $params['copy']['albums'] = $albumController->getAlbumData();
        $initImageController = new InitImageController();
        $initImagesData = $initImageController->getInitImagesData();
        if (isset($initImagesData['demo'])) {
            unset($initImagesData['demo']);
        }
        $params['copy']['init_images'] = $initImagesData;
        $params['breadcrumbs'] = [
            [
                'title' => 'Home',
                'url' => '/',
                'active' => true
            ]
        ];
        $params['title'] = 'Home';
        $params['images_title'] = 'Last Generated Images';
        $params['template'] = 'home.php';

        $this->render($params);
    }

    /**
     * Render images by type
     *
     * @param string $type Type
     * @return void
     */
    public function renderImagesByType(string $type): void
    {
        $params = $this->prepareParams();
        $fileController = new FileController();
        $params['type'] = $type;
        $params['images'] = $fileController->getImagesByType($type);
        $params['type_directories'] = $fileController->getTypeDirectories($type);
        $albumController = new AlbumController();
        $params['copy']['albums'] = $albumController->getAlbumData();
        $initImageController = new InitImageController();
        $initImagesData = $initImageController->getInitImagesData();
        if (isset($initImagesData['demo'])) {
            unset($initImagesData['demo']);
        }
        $params['copy']['init_images'] = $initImagesData;
        $params['breadcrumbs'] = [
            [
                'title' => 'Home',
                'url' => '/',
                'active' => true
            ],
            [
                'title' => $type,
                'url' => '/' . $type,
                'active' => false
            ]
        ];
        $params['title'] = $type;
        $params['images_title'] = 'Last Generated ' . $type . ' Images';
        $params['template'] = 'images_by_type.php';

        $this->render($params);
    }

    /**
     * Render by type and date time
     *
     * @param string $type Type
     * @param string $dateTime Date time
     * @return void
     */
    public function renderImagesByTypeAndDateTime(string $type, string $dateTime): void
    {
        $params = $this->prepareParams();
        $params['type'] = $type;
        $params['date_time'] = $dateTime;
        $fileController = new FileController();
        $params['images'] = $fileController->getImagesByTypeAndDateTime($type, $dateTime);
        $params['type_directories'] = $fileController->getTypeDirectories($type);
        $albumController = new AlbumController();
        $params['copy']['albums'] = $albumController->getAlbumData();
        $initImageController = new InitImageController();
        $initImagesData = $initImageController->getInitImagesData();
        if (isset($initImagesData['demo'])) {
            unset($initImagesData['demo']);
        }
        $params['copy']['init_images'] = $initImagesData;
        $params['breadcrumbs'] = [
            [
                'title' => 'Home',
                'url' => '/',
                'active' => true
            ],
            [
                'title' => $type,
                'url' => '/' . $type,
                'active' => true
            ],
            [
                'title' => $dateTime,
                'url' => '/' . $type . '/' . $dateTime,
                'active' => false
            ]
        ];
        $params['title'] = $dateTime;
        $params['images_title'] = $dateTime;
        $params['template'] = 'images_by_type_and_date_time.php';

        $this->render($params);
    }

    /**
     * Render images by checkpoints
     *
     * @return void
     */
    public function renderImagesByCheckpoints(): void
    {
        $params = $this->prepareParams();
        $fileController = new FileController();
        $params['used_checkpoints'] = $fileController->getCheckpoints();
        $params['images_by_checkpoints'] = $fileController->getImagesByCheckpoints();
        $params['images'] = [];
        foreach ($params['images_by_checkpoints'] as $checkpoint => $images) {
            $params['images'] = array_merge($params['images'], $images);
        }
        $albumController = new AlbumController();
        $params['copy']['albums'] = $albumController->getAlbumData();
        $initImageController = new InitImageController();
        $initImagesData = $initImageController->getInitImagesData();
        if (isset($initImagesData['demo'])) {
            unset($initImagesData['demo']);
        }
        $params['copy']['init_images'] = $initImagesData;
        $params['breadcrumbs'] = [
            [
                'title' => 'Home',
                'url' => '/',
                'active' => true
            ],
            [
                'title' => 'Checkpoints',
                'url' => '/checkpoints',
                'active' => false
            ]
        ];
        $params['title'] = 'Checkpoints';
        $params['images_title'] = 'Checkpoints';
        $params['template'] = 'images_by_checkpoints.php';

        $this->render($params);
    }

    /**
     * Render images by checkpoint
     *
     * @param string $checkpoint Checkpoint
     * @return void
     */
    public function renderImagesByCheckpoint(string $checkpoint): void
    {
        $params = $this->prepareParams();
        $fileController = new FileController();
        $params['used_checkpoints'] = $fileController->getCheckpoints();
        $params['images'] = $fileController->getImagesByCheckpoint($checkpoint);
        $albumController = new AlbumController();
        $params['copy']['albums'] = $albumController->getAlbumData();
        $initImageController = new InitImageController();
        $initImagesData = $initImageController->getInitImagesData();
        if (isset($initImagesData['demo'])) {
            unset($initImagesData['demo']);
        }
        $params['copy']['init_images'] = $initImagesData;
        $params['breadcrumbs'] = [
            [
                'title' => 'Home',
                'url' => '/',
                'active' => true
            ],
            [
                'title' => 'Checkpoints',
                'url' => '/checkpoints',
                'active' => true
            ],
            [
                'title' => $checkpoint,
                'url' => '/checkpoints/' . $checkpoint,
                'active' => false
            ]
        ];
        $params['title'] = $checkpoint;
        $params['images_title'] = $checkpoint;
        $params['template'] = 'images_by_checkpoint.php';

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
        unset($requestIndex[0]);
        unset($requestIndex[0]);
        unset($requestIndex[0]);

        $params = $this->prepareParams();
        $albumController = new AlbumController();
        $params['album_sub_albums'] = $albumController->getAlbumSubdirectories();
        $params['images'] = $albumController->getAlbumImages();
        $params['copy']['albums'] = $albumController->getAlbumData();
        $initImageController = new InitImageController();
        $initImagesData = $initImageController->getInitImagesData();
        if (isset($initImagesData['demo'])) {
            unset($initImagesData['demo']);
        }
        $params['copy']['init_images'] = $initImagesData;
        $params['request_index'] = $requestIndex;
        $params['breadcrumbs'] = [
            [
                'title' => 'Home',
                'url' => '/',
                'active' => true
            ],
        ];
        $slug = '/';
        foreach ($requestIndex as $index => $value) {
            if ($index === 1) {
                $slug .= 'album' . '/';
                $params['breadcrumbs'][] = [
                    'title' => 'Album',
                    'url' => rtrim($slug, '/'),
                    'active' => true
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
        if (count($requestIndex) === 1) {
            $params['title'] = 'Album';
        } else {
            $params['title'] = str_replace('_', ' ', end($requestIndex));
        }
        $params['images_title'] = str_replace('_', ' ', end($requestIndex));
        $params['template'] = 'album.php';

        $this->render($params);
    }

    /**
     * Render prompt merger
     *
     * @return void
     */
    public function renderPromptMerger(): void
    {
        $params = $this->prepareParams();
        $promptController = new PromptController();
        $params['prompt_merger_directories'] = $promptController->getPromptDirectories();
        $params['breadcrumbs'] = [
            [
                'title' => 'Home',
                'url' => '/',
                'active' => true
            ],
            [
                'title' => 'Prompt Merger',
                'url' => '/prompt-merger',
                'active' => false
            ],
        ];
        $params['title'] = 'Prompt Merger';
        $params['template'] = 'prompt_merger.php';

        $this->render($params);
    }

    /**
     * Render prompt merger file editor
     *
     * @param string $promptMergerDirectory Prompt merger directory
     * @return void
     */
    public function renderPromptMergerFileEditor(string $promptMergerDirectory): void
    {
        $params = $this->prepareParams();
        $promptController = new PromptController();
        $promptFiles = $promptController->getPromptFiles($promptMergerDirectory);
        $params['breadcrumbs'] = [
            [
                'title' => 'Home',
                'url' => '/',
                'active' => true
            ],
            [
                'title' => 'Prompt Merger',
                'url' => '/prompt-merger',
                'active' => false
            ],
            [
                'title' => $promptMergerDirectory,
                'url' => '/prompt-merger/' . $promptMergerDirectory,
                'active' => false
            ],
        ];
        $params['directory'] = $promptMergerDirectory;
        $params['files'] = $promptFiles;
        $params['title'] = $promptMergerDirectory;
        $params['template'] = 'prompt_merger_editor.php';

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
     * Render not found
     *
     * @return void
     */
    public function renderNotFound(): void
    {
        $params = $this->prepareParams();
        $params['template'] = 'not_found.php';

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