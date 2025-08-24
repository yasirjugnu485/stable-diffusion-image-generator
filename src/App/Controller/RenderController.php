<?php

declare(strict_types=1);

namespace App\Controller;

class RenderController
{
    public function render(array $params = []): void
    {
        ob_start();
        foreach ($params as $key => $value) {
            ${$key} = $value;
        }
        $params['template'] = 'home.php';
        include(ROOT_DIR . 'templates/main.php');
        $rendered = ob_get_contents() . "\n";
        ob_end_clean();

        echo $rendered;
    }
}