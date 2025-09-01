<?php

declare(strict_types=1);

namespace App\Controller;

class imagesController
{
    public static array $renderData = [];

    public function __construct()
    {
        $navbarController = new NavbarController();
        $navbar = $navbarController->getData();
        self::$renderData['navbar'] = $navbar;
    }

    public function renderByType(): void
    {
        $fileCollectorController = new FileCollectorController();
        $type = $fileCollectorController->getType();

        self::$renderData['data'] = $fileCollectorController->getFilesByType($type);
        self::$renderData['breadcrumbs'] = [
            [
                'title' => $type,
                'url' => '/' . $type,
                'active' => false
            ]
        ];
        self::$renderData['template'] = 'images.php';

        $this->render(self::$renderData);

        exit();
    }

    public function renderByTypeAndDateTime(): void
    {
        $fileCollectorController = new FileCollectorController();
        $type = $fileCollectorController->getType();
        $dateTime = $fileCollectorController->getDateTime();
        $dateTimeName = str_replace(':', '-', $dateTime);
        $dateTimeName = str_replace(' ', '_', $dateTimeName);

        self::$renderData['data'] = $fileCollectorController->getFilesByTypeAndDateTime();
        self::$renderData['breadcrumbs'] = [
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
        self::$renderData['template'] = 'images.php';

        $this->render(self::$renderData);

        exit();
    }

    public function renderByCheckpoint(): void
    {
        $fileCollectorController = new FileCollectorController();
        $checkpoint = $fileCollectorController->getCheckpoint();

        self::$renderData['data'] = $fileCollectorController->getFilesByCheckpoint();
        self::$renderData['breadcrumbs'] = [
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

        self::$renderData['header'] = 'checkpoints.json -> ' . $fileCollectorController->getCheckpoint();
        self::$renderData['template'] = 'images.php';

        $this->render(self::$renderData);

        exit();
    }

    private function render(array $params = []): void
    {
        $renderController = new RenderController();
        $renderController->render($params);
    }
}