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

use App\Interface\InitImagesCollectorInterface;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class InitImagesCollectorController implements InitImagesCollectorInterface
{
    /**
     * Initialize images data
     *
     * @var array|null
     */
    public static array|null $initImagesData = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->handleActions();
        $this->collectInitImages();
    }

    /**
     * Handle actions
     *
     * @return void
     */
    private function handleActions(): void
    {
        if (null === self::$initImagesData) {
            if (isset($_POST['action']) && $_POST['action'] === 'addInitImages') {
                $this->addInitImages();
            }
        }
    }

    /**
     * Collect all initialize images
     *
     * @return void
     */
    private function collectInitImages(): void
    {
        if (null === self::$initImagesData) {
            $initImagesData = $this->collectFiles(ROOT_DIR . 'init_images/');
            if (count($initImagesData)) {
                foreach ($initImagesData as $directory => $files) {
                    if (count($files)) {
                        sort($initImagesData[$directory]);
                    }
                }
                self::$initImagesData = $initImagesData;
            }
        }
    }

    /**
     * Collect files from directory
     *
     * @param string $directory Directory
     * @return array
     */
    public function collectFiles(string $directory): array
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $result = [];
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $pathname = explode('/', $item->getPathname());
                $result[end($pathname)] = [];
            }
        }
        foreach ($iterator as $item) {
            if ($item->isFile()) {
                if (!str_ends_with($item->getPathname(), '.png') &&
                    !str_ends_with($item->getPathname(), '.jpg') &&
                    !str_ends_with($item->getPathname(), '.jpeg')) {
                    continue;
                }
                $pathname = explode('/', $item->getPathname());
                $prompt = $pathname[count($pathname) - 2];
                $file = end($pathname);
                if (isset($result[$prompt])) {
                    $result[$prompt][] = $file;
                }
            }
        }

        return $result;
    }

    /**
     * Add init images
     *
     * @return void
     */
    protected function addInitImages(): void
    {
        $initImage = $_POST['initImages'];
        $match = preg_match('/^[a-zA-Z0-9_-]+$/', $initImage);
        if (!$match) {
            new ErrorController(self::ERROR_INIT_IMAGES_DIRECTORY_WRONG_NAME);
            return;
        } elseif (is_dir(ROOT_DIR . 'init_images/' . $initImage) ||
            is_file(ROOT_DIR . 'init_images/' . $initImage)) {
            new ErrorController(self::ERROR_INIT_IMAGES_DIRECTORY_EXISTS);
            return;
        }

        mkdir(ROOT_DIR . 'init_images/' . $initImage, 0777, true);

        new SuccessController(self::SUCCESS_INIT_IMAGES_DIRECTORY_CREATED);
    }

    /**
     * Get initialize images
     *
     * @return array
     */
    public function getInitImages(): array
    {
        return array_keys(self::$initImagesData);
    }

    /**
     * Get initialize images data
     *
     * @return array|null
     */
    public function getInitImagesData(): array|null
    {
        return self::$initImagesData;
    }
}