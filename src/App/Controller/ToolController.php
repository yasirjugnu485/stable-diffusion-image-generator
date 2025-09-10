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
     * Get URL
     *
     * @return string
     */
    public function getUrl(): string
    {
        if (str_starts_with($_SERVER['HTTP_REFERER'], 'https://')) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Delete directory
     *
     * @param string $directory Directory
     * @return void
     */
    public function deleteDirectory(string $directory): void
    {
        if (is_dir($directory)) {
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
    }

    /**
     * Generate random string
     *
     * @param int $length
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
     * Collect files from directory
     *
     * @param string $directory Directory
     * @return array
     */
    public function collectFileList(string $directory): array
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

        return $this->parsePathsOfFiles($result);
    }

    /**
     * Parse path of files
     *
     * @param array $array Collected file list
     * @return mixed
     */
    private function parsePathsOfFiles(array $array): mixed
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
}