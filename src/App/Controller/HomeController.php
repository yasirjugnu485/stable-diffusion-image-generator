<?php

declare(strict_types=1);

namespace App\Controller;

class HomeController
{
    public function __construct()
    {
        $fileCollectorController = new FileCollectorController();
        $data = $fileCollectorController->getLastFiles();
        $checkpoints = $fileCollectorController->collectUsedCheckpoints();
        $navbarController = new NavbarController();
        $navbar = $navbarController->getData();

        $this->render([
            'data' => $data,
            'navbar' => $navbar,
            'checkpoints' => $checkpoints,
            'title' => 'Home',
            'header' => 'Last generated images',
            'template' => 'home.php',
        ]);

        exit();
    }

    private function render(array $params = []): void
    {
        $renderController = new RenderController();
        $renderController->render($params);
    }
}