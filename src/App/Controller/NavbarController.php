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
            self::$navbarData = [];
            $this->collectNavbarData();
            $this->collectCheckpoints();
            $this->collectPrompts();
            $this->collectInitImages();
            $this->collectAlbums();
        }
    }

    /**
     * Collect navbar data
     *
     * @return void
     */
    private function collectNavbarData(): void
    {
        $fileController = new FileController();
        $fileData = $fileController->getFileData();
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
     * Collect checkpoints
     *
     * @return void
     */
    private function collectCheckpoints(): void
    {
        $fileController = new FileController();
        self::$navbarData['checkpoints'] = $fileController->getUsedCheckpoints();
    }

    /**
     * Collect prompts
     *
     * @return void
     */
    private function collectPrompts(): void
    {
        $promptController = new PromptController();
        self::$navbarData['prompts'] = $promptController->getPromptDirectories();
    }

    /**
     * Collect initialize images
     *
     * @return void
     */
    private function collectInitImages(): void
    {
        $initImagesController = new InitImageController();
        self::$navbarData['init_images'] = $initImagesController->getInitImagesDirectories();
    }

    /**
     * Collect albums
     *
     * @return void
     */
    private function collectAlbums(): void
    {
        $albumController = new AlbumController();
        self::$navbarData['albums'] = $albumController->collectRootDirectories();
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