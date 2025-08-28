<?php

declare(strict_types=1);

namespace App\Controller;

class ImagesController
{
    public function __construct()
    {
        $fileCollectorController = new FileCollectorController();
        $data = $fileCollectorController->getFilesByTypeAndDateTime();
        $navbar = $fileCollectorController->getNavbarData();

        $this->render([
            'data' => $data,
            'navbar' => $navbar,
        ]);

        exit();
    }

    private function render(array $params = []): void
    {
        $params['template'] = 'images.php';

        $renderController = new RenderController();
        $renderController->render($params);
    }
}