<?php

declare(strict_types=1);

namespace Controller;

class BootstrapController
{
    public function __construct()
    {
        $this->classLoader('src/Interface');
        $this->classLoader('src');
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
        $configController = new ConfigController();
        $config = $configController->getConfig();
        new PromptController();
        if ($config['mode'] === 'img2img') {
            new InitImagesController();
        }
        $executeController = new ExecuteController();
        $executeController->run();
    }
}