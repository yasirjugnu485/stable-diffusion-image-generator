<?php

declare(strict_types=1);

namespace App\Controller;

class HomeController
{
    public function __construct()
    {
        $fileCollectorController = new FileCollectorController();
        $data = $fileCollectorController->getLastFiles();
        $navbar = $fileCollectorController->getNavbarData();

        $this->render([
            'data' => $data,
            'navbar' => $navbar,
        ]);
    }

    private function render(array $params = []): void
    {
        $params['template'] = 'home.php';

        $renderController = new RenderController();
        $renderController->render($params);
    }
}