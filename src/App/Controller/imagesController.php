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

    public function renderByTypeAndDateTime(): void
    {
        $fileCollectorController = new FileCollectorController();
        self::$renderData['data'] = $fileCollectorController->getFilesByTypeAndDateTime();
        self::$renderData['header'] =
            $fileCollectorController->getType() . ' -> ' . $fileCollectorController->getDateTime();
        self::$renderData['template'] = 'images.php';

        $this->render(self::$renderData);

        exit();
    }

    public function renderByCheckpoint(): void
    {
        $fileCollectorController = new FileCollectorController();
        self::$renderData['data'] = $fileCollectorController->getFilesByCheckpoint();
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