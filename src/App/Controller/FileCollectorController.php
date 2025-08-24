<?php

declare(strict_types=1);

namespace App\Controller;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileCollectorController
{
    public static array|null $fileData = null;

    public function __construct()
    {
        $this->collectFiles();
    }

    private function collectFiles(): void
    {
        if (null === self::$fileData) {
            $fileData = $this->getFileList(ROOT_DIR . 'outputs/');
            if (!isset($fileData['loop'])) {
                $fileData['loop'] = [];
            }
            if (!isset($fileData['txt2img'])) {
                $fileData['txt2img'] = [];
            }
            if (!isset($fileData['img2img'])) {
                $fileData['img2img'] = [];
            }

            self::$fileData = $fileData;
        }
    }

    private function getFileList(string $directory, bool $asArray = true): array
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $result = [];
        foreach ($iterator as $item) {
            $path = $item->getPathname();
            if ($item->isFile()) {
                if (!str_ends_with($path, 'payloads.json')) {
                    continue;
                }
                $result[] = str_replace($directory, '', $path);
            }
        }

        return $asArray ? $this->parsePathsOfFiles($result) : $result;
    }

    private function parsePathsOfFiles($array)
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

    public function getLastFiles(): array
    {
        $result = [];

        $newest = 0;
        $targetType = false;
        $targetKey = false;
        foreach (self::$fileData['txt2img'] as $key => $file) {
            if (!$this->containsFiles(self::$fileData['txt2img'][$key])) {
                continue;
            }
            $time = strtotime($key);
            if ($time > $newest) {
                $targetType = 'txt2img';
                $targetKey = $key;
            }
        }
        foreach (self::$fileData['img2img'] as $key => $file) {
            if (!$this->containsFiles(self::$fileData['img2img'][$key])) {
                continue;
            }
            $time = strtotime($key);
            if ($time > $newest) {
                $targetType = 'img2img';
                $targetKey = $key;
            }
        }
        foreach (self::$fileData['loop'] as $key => $file) {
            if (!$this->containsFiles(self::$fileData['loop'][$key])) {
                continue;
            }
            $time = strtotime($key);
            if ($time > $newest) {
                $targetType = 'loop';
                $targetKey = $key;
            }
        }

        if ($targetType && $targetKey) {
            $result['type'] = $targetType;
            $result['payloads'] = $this->getData($targetType, $targetKey);
        }

        return $result;
    }

    private function containsFiles(array $array): bool
    {
        foreach ($array as $fileOrArray) {
            if (is_array($fileOrArray)) {
                if ($this->containsFiles($fileOrArray)) {
                    return true;
                }
            } else {
                if (str_ends_with($fileOrArray, 'payloads.json')) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getData(string $type, string $key): array
    {
        return json_decode(
            file_get_contents(ROOT_DIR . 'outputs/' . $type . '/' . $key . '/payloads.json'), true
        );
    }
}