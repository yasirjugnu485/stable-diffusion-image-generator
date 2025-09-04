<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Exception\PromptImageGeneratorException;
use Cli\Exception\StableDiffusionServiceException;
use Cli\Interface\BootstrapInterface;
use DateTime;
use Throwable;

include_once(ROOT_DIR . 'src/Cli/Interface/BootstrapInterface.php');

class BootstrapController implements BootstrapInterface
{
    /**
     * Arguments and argument parameters
     *
     * @var array|null
     */
    private static array|null $arguments = null;

    /**
     * Constructor
     *
     * @return void
     * @throws PromptImageGeneratorException|StableDiffusionServiceException
     */
    public function __construct()
    {
        if (null === self::$arguments) {
            self::$arguments = [];

            $this->classLoader(ROOT_DIR . 'src/Cli/Interface');
            $this->classLoader(ROOT_DIR . 'src/Cli');

            $this->initArguments();

            $useArguments = false;
            if (array_key_exists('--help', self::$arguments) || array_key_exists('-h', self::$arguments)) {
                $this->help();
            } elseif (array_key_exists('--start-web-application', self::$arguments)) {
                $this->startBuildInWebServer(true);
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
    }

    /**
     * Initialize arguments and argument parameters
     *
     * @return void
     * @throws PromptImageGeneratorException
     */
    private function initArguments(): void
    {
        $possibleArguments = [
            'run.php',
            '--help',
            '-h',
            '--start-web-application',
            '--kill',
            '-k',
            '--config',
            '-c',
        ];

        if (isset($_SERVER['argv'])) {
            for ($i = 0; $i < count($_SERVER['argv']); $i++) {
                if (!in_array($_SERVER['argv'][$i], $possibleArguments)) {
                    throw new PromptImageGeneratorException(sprintf(
                        self::ERROR_UNKNOWN_ARGUMENT,
                        $_SERVER['argv'][$i]
                    ));
                }
                if ($_SERVER['argv'][$i] === '--config' || $_SERVER['argv'][$i] === '-c') {
                    $iplus = $i + 1;
                    if (!isset($_SERVER['argv'][$iplus])) {
                        throw new PromptImageGeneratorException(self::ERROR_UNKNOWN_CONFIG);
                    } elseif (!file_exists($_SERVER['argv'][$iplus])) {
                        throw new PromptImageGeneratorException(
                            sprintf(self::ERROR_CUSTOM_CONFIG_NOT_FOUND, $_SERVER['argv'][$iplus])
                        );
                    }
                    self::$arguments[$_SERVER['argv'][$i]] = [$_SERVER['argv'][$iplus]];
                    $i++;
                } else {
                    self::$arguments[$_SERVER['argv'][$i]] = [];
                }
            }
        }
    }

    /**
     * Class loader
     *
     * @param string $directory Class directory
     * @return void
     */
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
                        include_once($directory . '/' . $file);
                    }
                }
            }
        }
    }

    /**
     * Display help
     *
     * @return void
     */
    private function help(): void
    {
        new EchoController(file_get_contents(ROOT_DIR . 'scripts/help.txt'));
        new EchoController();

        exit();
    }

    /**
     * Start build-in web server
     *
     * @param bool $startOnly Only start the server and exit
     * @return void
     * @throws PromptImageGeneratorException
     */
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
                exec('php -S 0.0.0.0:8000 -t public/ > /dev/null 2>&1 &');
                new EchoController(self::SUCCESS_START_BUILD_IN_SERVER);
            } catch (Throwable $throwable) {
                new EchoController(self::ERROR_START_BUILD_IN_SERVER);
            }
        }

        if ($startOnly) {
            exit();
        }
    }

    /**
     * Run CLI application silent in background
     *
     * @return void
     */
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

    /**
     * Run CLI application
     *
     * @return void
     * @throws PromptImageGeneratorException|StableDiffusionServiceException
     */
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

    /**
     * Get arguments and argument parameters
     *
     * @return array
     */
    public function getArguments(): array
    {
        return self::$arguments;
    }
}