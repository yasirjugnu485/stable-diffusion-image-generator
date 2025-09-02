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

class NavbarController
{
    /**
     * Navbar data
     *
     * @var array|null
     */
    private static array|null $navbarData = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        if (self::$navbarData === null) {
            $this->collectCheckpoints();
            $this->collectNavbarData();
            $this->collectPrompts();
            $this->collectInitImages();
        }
    }

    /**
     * Collect checkpoints
     *
     * @return void
     */
    private function collectCheckpoints(): void
    {
        if (self::$navbarData === null) {
            self::$navbarData = [];
        }

        $fileCollectorController = new FileCollectorController();
        self::$navbarData['checkpoints'] = $fileCollectorController->collectUsedCheckpoints();
    }

    /**
     * Collect navbar data
     *
     * @return void
     */
    private function collectNavbarData(): void
    {
        if (self::$navbarData === null) {
            self::$navbarData = [];
        }

        $fileCollectorController = new FileCollectorController();
        $fileData = $fileCollectorController->getFileData();
        self::$navbarData['types'] = [];
        foreach ($fileData as $type => $files) {
            self::$navbarData['types'][$type] = [];
            foreach ($files as $key => $file) {
                $entry = str_replace(' ', '_', $key);
                $entry = str_replace(':', '-', $entry);
                self::$navbarData['types'][$type][] = [
                    'name' => $key,
                    'slug' => $entry
                ];
            }
        }
    }

    /**
     * Collect prompts
     *
     * @return void
     */
    private function collectPrompts(): void
    {
        $promptCollectorController = new PromptCollectorController();
        self::$navbarData['prompts'] = $promptCollectorController->getPrompts();
    }

    /**
     * Collect initialize images
     *
     * @return void
     */
    private function collectInitImages(): void
    {
        $initImagesCollectorController = new InitImagesCollectorController();
        self::$navbarData['init_images'] = $initImagesCollectorController->getInitImages();
    }

    /**
     * Get navbar data
     *
     * @return array
     */
    public function getData(): array
    {
        return self::$navbarData;
    }
}