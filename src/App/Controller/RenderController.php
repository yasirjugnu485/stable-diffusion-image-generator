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
        eval("?> '../templates/main.php' <?php ");
        $rendered = ob_get_contents() . "\n";
        ob_end_clean();

        echo $rendered;
    }
}