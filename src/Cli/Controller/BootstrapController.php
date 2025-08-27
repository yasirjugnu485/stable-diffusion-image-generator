<?php

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Exception\PromptImageGeneratorException;
use Cli\Interface\BootstrapInterface;
use DateTime;
use Throwable;

include_once (ROOT_DIR . 'src/Cli/Interface/BootstrapInterface.php');

class BootstrapController implements BootstrapInterface
{
    private static array $arguments = [];

    public function __construct()
    {
        $this->classLoader(ROOT_DIR . 'src/Cli/Interface');
        $this->classLoader(ROOT_DIR . 'src/Cli');

        $this->getArguments();

        $useArguments = false;
        if (array_key_exists('--help', self::$arguments) || array_key_exists('-h', self::$arguments)) {
            $this->help();
        } elseif (array_key_exists('--start-web-application', self::$arguments)) {
            $this->startBuildInWebServer(true);
        } elseif (array_key_exists('--silent', self::$arguments) || array_key_exists('-s', self::$arguments)) {
            $this->runSilent();
        }

        if (!$useArguments) {
            new EchoController(self::ECHO_START_APPLICATION);
            new EchoController(self::ECHO_START_BY);
            $this->startBuildInWebServer();
            new EchoController(self::SUCCESS_START_APPLICATION);
            new EchoController();
            $this->run();
        }
    }

    private function getArguments(): void
    {
        $possibleArguments = [
            'run.php',
            '--help',
            '-h',
            '--start-web-application',
            '--silent',
            '-s',
            '--kill',
            '-k',
        ];

        if (isset($_SERVER['argv'])) {
           for ($i= 0; $i < count($_SERVER['argv']); $i++) {
               if (!in_array($_SERVER['argv'][$i], $possibleArguments)) {
                   Throw new PromptImageGeneratorException(sprintf(
                       self::ERROR_UNKNOWN_ARGUMENT,
                       $_SERVER['argv'][$i]
                   ));
               }
               self::$arguments[$_SERVER['argv'][$i]] = [];
           }
        }
    }

    private function classLoader(string $directory): void
    {
        if (is_dir($directory)) {
            $scan = scandir($directory);
            unset($scan[0], $scan[1]);
            foreach ($scan as $file) {
                if (is_dir($directory . '/' . $file)) {
                    $this->classLoader($directory . '/' . $file);
                } else {
                    if (strpos($file, '.php') !== false) {
                        include_once($directory. '/' . $file);
                    }
                }
            }
        }
    }

    private function help(): void
    {
        new EchoController(file_get_contents(ROOT_DIR . 'scripts/help.txt'));
        new EchoController();

        exit();
    }

    private function startBuildInWebServer(bool $startOnly = false): void
    {
        if (!$startOnly) {
            $configController = new ConfigController();
            $config = $configController->getConfig();
        } else {
            $config['startWebApplication'] = true;
        }

        if ($config['startWebApplication']) {
            new EchoController(self::ECHO_TRY_START_BUILD_IN_SERVER);
            try {
                exec('php -S 127.0.0.1:8000 -t public/ > /dev/null 2>&1 &');
                new EchoController(self::SUCCESS_START_BUILD_IN_SERVER);
            } catch (Throwable $throwable) {
                new EchoController(self::ERROR_START_BUILD_IN_SERVER);
            }
        }

        if ($startOnly) {
            exit();
        }
    }

    private function runSilent(): void
    {
        try {
            $dateTime = new DateTime('NOW');
            $task = $dateTime->format('Y-m-d_H-i-s');
            exec('nohup php run.php &> task_' . $task . '.log &');
        } catch (Throwable $throwable) {
            new EchoController($throwable->getMessage());
        }

        exit();
    }

    private function run(): void
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