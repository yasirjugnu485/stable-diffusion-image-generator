<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace App\Controller;

class BootstrapController
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
//        if (isset($_GET['debug']) && $_GET['debug'] === '1') {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
//        }

        session_start();

        $this->classLoader(ROOT_DIR . 'src/App/Interface');
        $this->classLoader(ROOT_DIR . 'src/App');
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
     * Run application
     *
     * @return void
     */
    public function run(): void
    {
        new FileController();
        new PromptController();
        new InitImageController();
        new UriController();
    }
}