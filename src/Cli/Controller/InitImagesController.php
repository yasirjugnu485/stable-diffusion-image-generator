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
use Cli\Interface\InitImagesInterface;
use Random\RandomException;
use Throwable;

class InitImagesController implements InitImagesInterface
{
    /**
     * Initialize images data
     *
     * @var array
     */
    private static array $initImagesData = [];

    /**
     * Initialize images in base64
     *
     * @var array|null
     */
    private static array|null $initImagesBase64 = [];

    /**
     * Current initialize images index
     *
     * @var int|null
     */
    private static int|null $currentInitImagesIndex = null;

    /**
     * Next initialize image
     *
     * @var string|null
     */
    private static string|null $nextImage = null;

    /**
     * Next initialize image in base64
     *
     * @var string|null
     */
    private static string|null $nextImageBase64 = null;

    /**
     * Constructor
     *
     * @return void
     * @throws PromptImageGeneratorException
     */
    public function __construct()
    {
        new ConfigController();
        $config = (new ConfigController())->getConfig();

        if (count(self::$initImagesData) > 0 || ($config['mode'] !== 'img2img' && !$config['loop'])) {
            return;
        }

        $this->initInitImagesData();
    }

    /**
     * Initialize initialize images data
     *
     * @return void
     * @throws PromptImageGeneratorException
     */
    private function initInitImagesData(): void
    {
        new EchoController(self::ECHO_INIT_INIT_IMAGES);

        new ConfigController();
        $config = (new ConfigController())->getConfig();

        if (!is_dir(ROOT_DIR . 'init_images')) {
            throw new PromptImageGeneratorException(self::ERROR_NO_INIT_IMAGES_DIRECTORY_FOUND);
        }

        $initImagesDirectories = array_filter(glob(ROOT_DIR . 'init_images/*'), 'is_dir');
        if (empty($initImagesDirectories)) {
            throw new PromptImageGeneratorException(self::ERROR_NO_INIT_IMAGES_SUBDIRECTORIES_FOUND);
        }

        $initImagesComplete = [];
        foreach ($initImagesDirectories as $initImagesDirectory) {
            $name = str_replace(ROOT_DIR . 'init_images/', '', $initImagesDirectory);
            $files = array_filter(glob($initImagesDirectory . '/*'), 'is_file');
            if (empty($files)) {
                continue;
            }
            foreach ($files as $file) {
                if (str_ends_with($file, '.png') || str_ends_with($file, '.jpg') || str_ends_with($file, '.jpeg')) {
                    if (!isset($initImagesComplete[$name])) {
                        $initImagesComplete[$name] = [];
                    }
                    $initImagesComplete[$name][] = $file;
                }
            }
        }
        if (empty($initImagesComplete)) {
            throw new PromptImageGeneratorException(self::ERROR_NO_INIT_IMAGES_DATA_FOUND);
        }

        if ($config['initImages'] !== null) {
            if (!array_key_exists($config['initImages'], $initImagesComplete)) {
                throw new PromptImageGeneratorException(self::ERROR_CONFIGURED_INIT_IMAGES_NOT_FOUND);
            }
            self::$initImagesData = $initImagesComplete[$config['initImages']];
        } else {
            foreach ($initImagesComplete as $item) {
                foreach ($item as $file) {
                    self::$initImagesData[] = $file;
                }
            }
        }

        new EchoController(self::SUCCESS_INIT_INIT_IMAGES);
        new EchoController();
    }

    /**
     * Get next initialize image
     *
     * @return string
     * @throws PromptImageGeneratorException
     * @throws RandomException
     */
    public function getNextInitImage(): string
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();
        if ($config['loop'] && self::$nextImage) {
            return self::$nextImageBase64;
        }

        if ($config['initImages'] === null) {
            self::$currentInitImagesIndex = random_int(0, count(self::$initImagesData) - 1);
        } else {
            self::$currentInitImagesIndex =
                is_null(self::$currentInitImagesIndex)
                    ? 0
                    : self::$currentInitImagesIndex + 1;
            if (self::$currentInitImagesIndex >= count(self::$initImagesData)) {
                self::$currentInitImagesIndex = 0;
            }
        }

        return $this->getInitImagesBase64(self::$currentInitImagesIndex);
    }

    /**
     * Get initialize image in base64
     *
     * @param int $currentInitImagesIndex Current initialize images index
     * @return string
     */
    private function getInitImagesBase64(int $currentInitImagesIndex): string
    {
        if (!isset(self::$initImagesBase64[$currentInitImagesIndex])) {
            $file = self::$initImagesData[$currentInitImagesIndex];
            try {
                if (str_ends_with($file, '.png')) {
                    self::$initImagesBase64[$currentInitImagesIndex] = $this->png2base64($file);
                } elseif (str_ends_with($file, '.jpg') || str_ends_with($file, '.jpeg')) {
                    self::$initImagesBase64[$currentInitImagesIndex] = $this->jpg2base64($file);
                }
            } catch (Throwable $throwable) {
                new PromptImageGeneratorException(self::ERROR_PHP_GD_MISSING);
            }
        }

        return self::$initImagesBase64[$currentInitImagesIndex];
    }

    /**
     * Convert jpg to base64
     *
     * @param string $file File
     * @return string
     */
    private function jpg2base64(string $file): string
    {
        $image = imagecreatefromjpeg($file);
        ob_start();
        imagepng($image);
        $data = ob_get_contents();
        ob_end_clean();

        return base64_encode($data);
    }

    /**
     * Convert png to base64
     *
     * @param string $file File
     * @return string
     */
    private function png2base64(string $file): string
    {
        $image = imagecreatefrompng($file);
        ob_start();
        imagepng($image);
        $data = ob_get_contents();
        ob_end_clean();

        return base64_encode($data);
    }

    /**
     * Set next initialize image
     *
     * @param string $nextImage Next initialize image
     * @param string $imageBase64 Image base64
     * @return void
     */
    public function setNextImage(string $nextImage, string $imageBase64): void
    {
        self::$nextImage = $nextImage;
        self::$nextImageBase64 = $imageBase64;
    }

    /**
     * Get current initialize image file
     *
     * @return string
     */
    public function getCurrentInitImageFile(): string
    {
        return self::$nextImage ?? self::$initImagesData[self::$currentInitImagesIndex];
    }
}