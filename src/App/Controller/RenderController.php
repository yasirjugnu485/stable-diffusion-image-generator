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
        return [
            'navbar' => $navbar,
        ];
    }

    /**
     * Render by type
     *
     * @return void
     */
    public function renderByType(): void
    {
        $params = $this->prepareParams();

        $fileCollectorController = new FileCollectorController();
        $type = $fileCollectorController->getType();

        $params['data'] = $fileCollectorController->getFilesByType($type);
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
     * @return void
     */
    public function renderByTypeAndDateTime(): void
    {
        $params = $this->prepareParams();

        $fileCollectorController = new FileCollectorController();
        $type = $fileCollectorController->getType();
        $dateTime = $fileCollectorController->getDateTime();
        $dateTimeName = str_replace(':', '-', $dateTime);
        $dateTimeName = str_replace(' ', '_', $dateTimeName);

        $params['data'] = $fileCollectorController->getFilesByTypeAndDateTime();
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
     * Render by checkpoint
     *
     * @return void
     */
    public function renderByCheckpoint(): void
    {
        $params = $this->prepareParams();

        $fileCollectorController = new FileCollectorController();
        $checkpoint = $fileCollectorController->getCheckpoint();

        $params['data'] = $fileCollectorController->getFilesByCheckpoint();
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
     * Render sets of checkpoints
     *
     * @return void
     */
    public function renderCheckpoints(): void
    {
        $params = $this->prepareParams();

        $fileCollectorController = new FileCollectorController();
        $params['checkpoints'] = $fileCollectorController->collectUsedCheckpoints();
        $params['data'] = $fileCollectorController->getCheckpointFiles();
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