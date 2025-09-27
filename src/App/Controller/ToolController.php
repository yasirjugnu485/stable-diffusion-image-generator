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

use FilesystemIterator;
use Random\RandomException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ToolController
{
    /**
     * Get current URL
     *
     * @return string
     */
    public function getCurrentUrl(): string
    {
        $httpReferer = $_SERVER['HTTP_REFERER'] ?? 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (str_starts_with($httpReferer, 'https://')) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Recursively delete directory with all subdirectories and files
     *
     * @param string $directory Directory
     * @return void
     */
    public function deleteDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $files = scandir($directory);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $directory . '/' . $file;
                if (is_dir($filePath)) {
                    $this->deleteDirectory($filePath);
                } else {
                    unlink($filePath);
                }
            }
        }
        rmdir($directory);
    }

    /**
     * Generate random string
     *
     * @param int $length String length
     * @return string
     * @throws RandomException
     */
    public function generateRandomString(int $length = 32): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * Collect data.json files from directory
     *
     * @param string $directory Directory
     * @param bool $asRecursiveArray Return as recursive array
     * @return array
     */
    public function collectDataFiles(string $directory, bool $asRecursiveArray = true): array
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $result = [];
        foreach ($iterator as $item) {
            $path = $item->getPathname();
            if ($item->isFile()) {
                if (!str_ends_with($path, 'data.json')) {
                    continue;
                }
                $result[] = str_replace($directory, '', $path);
            }
        }

        return $asRecursiveArray ? $this->parseDataFiles($result) : $result;
    }

    /**
     * Parse data.json files
     *
     * @param array $array Collected data.json files from directory
     * @return mixed
     */
    private function parseDataFiles(array $array): mixed
    {
        rsort($array);
        $result = array();

        foreach ($array as $item) {
            $parts = explode('/', $item);
            $current = &$result;
            for ($i = 1, $max = count($parts); $i < $max; $i++) {
                if (!isset($current[$parts[$i - 1]])) {
                    $current[$parts[$i - 1]] = array();
                }
                $current = &$current[$parts[$i - 1]];
            }
            $last = end($parts);
            if (!isset($current[$last]) && $last) {
                $current[] = end($parts);
            }
        }

        return $result;
    }

    /**
     * Check if array contains data.json files
     *
     * @param array $array Files
     * @return bool
     */
    public function containsDataFiles(array $array): bool
    {
        foreach ($array as $fileOrArray) {
            if (is_array($fileOrArray)) {
                if ($this->containsDataFiles($fileOrArray)) {
                    return true;
                }
            } else {
                if (str_ends_with($fileOrArray, 'data.json')) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Load images from data file
     *
     * @param string $dataFile Data file
     * @return array
     */
    public function loadImagesFromDataFile(string $dataFile): array
    {
        if (!file_exists($dataFile)) {
            return [];
        }

        return json_decode(file_get_contents($dataFile), true);
    }

    /**
     * Collect checkpoints from data.json files
     *
     * @param array $dataFiles data.json files
     * @return array
     */
    public function collectCheckpointsFromDataFiles(array $dataFiles): array
    {
        $checkpoints = [];
        foreach ($dataFiles as $dataFile) {
            if (isset($dataFile['data']['override_settings']['sd_model_checkpoint'])) {
                if (!in_array($dataFile['data']['override_settings']['sd_model_checkpoint'], $checkpoints)) {
                    $checkpoints[] = $dataFile['data']['override_settings']['sd_model_checkpoint'];
                }
            }
        }

        return $checkpoints;
    }

    /**
     * Collect refiner checkpoints from data.json files
     *
     * @param array $dataFiles data.json files
     * @return array
     */
    public function collectRefinerCheckpointsFromDataFiles(array $dataFiles): array
    {
        $checkpoints = [];
        foreach ($dataFiles as $dataFile) {
            if (isset($dataFile['data']['refiner_checkpoint'])) {
                if (!in_array($dataFile['data']['refiner_checkpoint'], $checkpoints)) {
                    $checkpoints[] = $dataFile['data']['refiner_checkpoint'];
                }
            }
        }

        return $checkpoints;
    }
}