<?php

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Interface\BootstrapInterface;
use Throwable;

include_once ('src/Cli/Interface/BootstrapInterface.php');

class BootstrapController implements BootstrapInterface
{
    public function __construct()
    {
        $this->classLoader(__DIR__ . 'src/Cli/Interface');
        $this->classLoader('src/Cli');

        new EchoController(self::ECHO_START_APPLICATION);
        new EchoController(self::ECHO_START_BY);
        $this->startBuildInWebServer();
        new EchoController(self::SUCCESS_START_APPLICATION);

        new EchoController();
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

    public function startBuildInWebServer(): void
    {
        new EchoController(self::ECHO_TRY_START_BUILD_IN_SERVER);
        try {
            exec('php -S 127.0.0.1:8000 -t public/ > /dev/null 2>&1 &');
            new EchoController(self::SUCCESS_START_BUILD_IN_SERVER);
        } catch(Throwable $throwable) {
            new EchoController(self::ERROR_START_BUILD_IN_SERVER);
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