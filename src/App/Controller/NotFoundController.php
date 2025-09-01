<?php

declare(strict_types=1);

namespace App\Controller;

class NotFoundController
{
    public function __construct()
    {
        $navbarController = new NavbarController;
        $navbar = $navbarController->getData();

        $this->render([
            'navbar' => $navbar,
        ]);
    }

    private function render(array $params = []): void
    {
        $params['template'] = 'not_found.php';

        $renderController = new RenderController();
        $renderController->render($params);
    }
}