<?php

declare(strict_types=1);

namespace Controller;

use Exception\PromptImageGeneratorException;
use Interface\InitImagesInterface;
use Throwable;

class InitImagesController implements InitImagesInterface
{
    private static array $initImagesData = [];

    private static array|null $initImagesBase64 = [];

    private static string|null $lastInitImages = null;

    private static string|null $currentInitImages = null;

    public function __construct()
    {
        if (count(self::$initImagesData) > 0) {
            return;
        }

        $this->initInitImagesData();
    }

    private function initInitImagesData(): void
    {
        new EchoController(self::ECHO_INIT_INIT_IMAGES);

        new ConfigController();
        $config = (new ConfigController())->getConfig();

        if (!is_dir('prompt')) {
            throw new PromptImageGeneratorException(self::ERROR_NO_INIT_IMAGES_DIRECTORY_FOUND);
        }

        $initImagesDirectories = array_filter(glob('init_images/*'), 'is_dir');
        if (empty($initImagesDirectories)) {
            throw new PromptImageGeneratorException(self::ERROR_NO_INIT_IMAGES_SUBDIRECTORIES_FOUND);
        }

        $initImagesData = [];
        foreach ($initImagesDirectories as $initImagesDirectory) {
            $name = str_replace('init_images/', '', $initImagesDirectory);
            $files = array_filter(glob($initImagesDirectory . '/*'), 'is_file');
            if (empty($files)) {
                continue;
            }
            foreach ($files as $file) {
                if (str_ends_with($file, '.png') || str_ends_with($file, '.jpg') || str_ends_with($file, '.jpeg')) {
                    if (!isset($initImagesData[$name])) {
                        $initImagesData[$name] = [];
                    }
                    $initImagesData[$name][] = $file;
                }
            }
        }
        if (empty($initImagesData)) {
            throw new PromptImageGeneratorException(self::ERROR_NO_INIT_IMAGES_DATA_FOUND);
        }

        self::$initImagesData = $initImagesData;
        if ($config['initImages'] !== null) {
            if (!array_key_exists($config['initImages'], self::$initImagesData)) {
                throw new PromptImageGeneratorException(self::ERROR_CONFIGURED_INIT_IMAGES_NOT_FOUND);
            }
            self::$currentInitImages = $config['initImages'];
        } else {
            self::$currentInitImages = array_key_first(self::$initImagesData);
        }

        new EchoController(self::SUCCESS_INIT_INIT_IMAGES);
        new EchoController();
    }

    public function getNextInitImages(): array
    {
        self::$lastInitImages = self::$currentInitImages;

        $initImages = $this->getInitImagesBase64(self::$currentInitImages);;

        $configController = new ConfigController();
        $config = $configController->getConfig();
        if ($config['initImages'] === null) {
            $currentInitImages = self::$currentInitImages;
            self::$currentInitImages = null;
            $next = false;
            foreach (self::$initImagesData as $initImagesKey => $initImagesData) {
                if ($next) {
                    self::$currentInitImages = $initImagesKey;
                    break;
                } elseif ($currentInitImages === $initImagesKey) {
                    $next = true;
                }
            }
            if (self::$currentInitImages === null) {
                self::$currentInitImages = array_key_first(self::$initImagesData);
            }
        }

        return $initImages;
    }

    private function getInitImagesBase64(string $initImages): array
    {
        if (!isset(self::$initImagesBase64[$initImages])) {
            foreach (self::$initImagesData[$initImages] as $file) {
                try {
                    if (str_ends_with($file, '.png')) {
                        self::$initImagesBase64[$initImages][] = $this->png2base64($file);
                    } elseif (str_ends_with($file, '.jpg') || str_ends_with($file, '.jpeg')) {
                        self::$initImagesBase64[$initImages][] = $this->jpg2base64($file);
                    }
                } catch (Throwable $throwable) {
                    new PromptImageGeneratorException(self::ERROR_PHP_GD_MISSING);
                }
            }
        }

        return self::$initImagesBase64[$initImages];
    }

    private function jpg2base64(string $file): string
    {
        $image = imagecreatefromjpeg($file);
        ob_start();
        imagepng($image);
        $data = ob_get_contents();
        ob_end_clean();

        return base64_encode($data);
    }

    private function png2base64(string $file): string
    {
        $image = imagecreatefrompng($file);
        ob_start();
        imagepng($image);
        $data = ob_get_contents();
        ob_end_clean();

        return base64_encode($data);
    }

    public function getLastInitImages(): string|null
    {
        return self::$lastInitImages;
    }
}