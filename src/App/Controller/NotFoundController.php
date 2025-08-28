<?php

declare(strict_types=1);

namespace App\Controller;

class NotFoundController
{
    public function __construct()
    {
        $fileCollectorController = new FileCollectorController();
        $navbar = $fileCollectorController->getNavbarData();

        $this->render([
            'navbar' => $navbar,
        ]);

        exit();
    }

    private function render(array $params = []): void
    {
        $params['template'] = 'not_found.php';

        $renderController = new RenderController();
        $renderController->render($params);
    }
}