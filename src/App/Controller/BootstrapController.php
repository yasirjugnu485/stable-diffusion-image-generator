<?php

declare(strict_types=1);

namespace App\Controller;

class BootstrapController
{
    public function __construct()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->classLoader(__DIR__ . '/../Controller');
    }

    private function classLoader(string $directory): void
    {
        if(is_dir($directory)) {
            $scan = scandir($directory);
            unset($scan[0], $scan[1]);
            foreach($scan as $file) {
                if(is_dir($directory . '/' . $file)) {
                    $this->classLoader($directory . '/' . $file);
                } else {
                    if(strpos($file, '.php') !== false) {
                        include_once($directory. '/' . $file);
                    }
                }
            }
        }
    }

    public function run(): void
    {
        $renderController = new RenderController();
        $renderController->render();
    }
}