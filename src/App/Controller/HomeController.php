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

class HomeController
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $fileController = new FileController();
        $data = $fileController->getLastFiles();
        $checkpoints = $fileController->collectUsedCheckpoints();
        $navbarController = new NavbarController();
        $navbar = $navbarController->getData();
        $successController = new SuccessController();
        $success = $successController->getSuccess();
        $errorController = new ErrorController();
        $error = $errorController->getError();
        $albumController = new AlbumController();
        $albumData = $albumController->getAlbumData();

        $breadcrumbs = [
            [
                'title' => 'Home',
                'url' => '/',
                'active' => true
            ]
        ];

        $this->render([
            'data' => $data,
            'navbar' => $navbar,
            'checkpoints' => $checkpoints,
            'album_data' => $albumData,
            'title' => 'Home',
            'breadcrumbs' => $breadcrumbs,
            'template' => 'home.php',
            'success' => $success,
            'error' => $error,
        ]);

        exit();
    }

    /**
     * Render
     *
     * @param array $params Parameters
     * @return void
     */
    private function render(array $params = []): void
    {
        $renderController = new RenderController();
        $renderController->render($params);
    }
}