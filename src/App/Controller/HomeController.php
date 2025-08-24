<?php

declare(strict_types=1);

namespace App\Controller;

class HomeController
{
    public function __construct()
    {
        $fileCollectorController = new FileCollectorController();
        $data = $fileCollectorController->getLastFiles();

        $this->render(['data' => $data]);
    }

    private function render(array $params = []): void
    {
        $params['template'] = ROOT_DIR . 'home.php';

        $renderController = new RenderController();
        $renderController->render($params);
    }
}