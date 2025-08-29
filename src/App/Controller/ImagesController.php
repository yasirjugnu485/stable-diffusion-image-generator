<?php

declare(strict_types=1);

namespace App\Controller;

class ImagesController
{
    public function __construct()
    {
        $fileCollectorController = new FileCollectorController();
        $data = $fileCollectorController->getFilesByTypeAndDateTime();
        $navbarController = new NavbarController();
        $navbar = $navbarController->getData();

        $this->render([
            'data' => $data,
            'navbar' => $navbar,
            'header' => $fileCollectorController->getType() . ' -> ' . $fileCollectorController->getDateTime(),
            'template' => 'images.php',
        ]);

        exit();
    }

    private function render(array $params = []): void
    {
        $renderController = new RenderController();
        $renderController->render($params);
    }
}