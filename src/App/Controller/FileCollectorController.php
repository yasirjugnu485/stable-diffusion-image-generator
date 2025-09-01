<?php

declare(strict_types=1);

namespace App\Controller;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileCollectorController
{
    public static array|null $fileData = null;

    public static array|null $payloads = null;

    public static array|null $filesByType = null;

    public static array|null $checkpointFiles = null;

    public static array|null $filesByTypeAndDateTime = null;

    public static string|null $type = null;

    public static string|null $dateTime = null;

    public static array|null $filesByCheckpoint = null;

    public static string|null $checkpoint = null;

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

        if (null === self::$payloads) {
            $this->collectPayloads();
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
                $newest = $time;
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
                $newest = $time;
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
                $newest = $time;
            }
        }

        if ($targetType && $targetKey) {
            $result['type'] = $targetType;
            $result['payloads'] = $this->getDataFromPayload($targetType, $targetKey);
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

    private function collectPayloads(): array
    {
        if (null === self::$payloads) {
            self::$payloads = [];
            foreach (self::$fileData as $type => $typeData) {
                foreach ($typeData as $dateTime => $payloads) {
                    if (file_exists(ROOT_DIR . 'outputs/' . $type . '/' . $dateTime . '/payloads.json')) {
                        self::$payloads = array_merge(self::$payloads, json_decode(
                            file_get_contents(
                                ROOT_DIR . 'outputs/' . $type . '/' . $dateTime . '/payloads.json'),
                            true
                        ));
                    }
                }
            }
        }

        return self::$payloads;
    }

    private function getDataFromPayload(string $type, string $key): array
    {
        $payloads = json_decode(
            file_get_contents(ROOT_DIR . 'outputs/' . $type . '/' . $key . '/payloads.json'), true
        );

        foreach ($payloads as $index => $payload) {
            if (isset($payload['file'])) {
                $split = explode('/outputs/' . $type . '/' . $key . '/', $payload['file']);
                $payloads[$index]['file'] = '/outputs/' . $type . '/' . $key . '/' . end($split);
            }
            if (isset($payload['payload']['init_images'])) {
                $split = explode('/init_images/', $payload['payload']['init_images']);
                $payloads[$index]['payload']['init_images'] = '/init_images/' . end($split);
            }
        }

        return $payloads;
    }

    public function collectFilesByType(string $type, int $limit = 1000): array
    {
        self::$filesByType = [];
        self::$filesByType['payloads'] = [];
        self::$type = $type;
        if (!isset(self::$fileData[$type])) {
            return [];
        }

        foreach (self::$payloads as $payload) {
            if ($payload['mode'] === $type) {
                $split = explode('/outputs/' . $type . '/', $payload['file']);
                $payload['file'] = '/outputs/' . $type . '/' . end($split);
                if (isset($payload['payload']['init_images'])) {
                    $split = explode('/init_images/', $payload['payload']['init_images']);
                    $payload['payload']['init_images'] = '/init_images/' . end($split);
                }
                self::$filesByType['payloads'][] = $payload;
                if (count(self::$filesByType) >= $limit) {
                    break;
                }
            }
        }

        return self::$filesByType;
    }

    public function collectCheckpointFiles(int $limit = 10): array
    {
        self::$checkpointFiles = [];

        foreach (self::$payloads as $payload) {
            if (isset($payload['payload']['override_settings']['sd_model_checkpoint'])) {
                $checkpoint = $payload['payload']['override_settings']['sd_model_checkpoint'];
                $type = $payload['mode'];
                if (!isset(self::$checkpointFiles[$checkpoint])) {
                    self::$checkpointFiles[$checkpoint] = [];
                }
                if (!isset(self::$checkpointFiles[$checkpoint]['payloads'])) {
                    self::$checkpointFiles[$checkpoint]['payloads'] = [];
                }
                if (count(self::$checkpointFiles[$checkpoint]['payloads']) >= $limit) {
                    continue;
                }
                $split = explode('/outputs/' . $type . '/', $payload['file']);
                $payload['file'] = '/outputs/' . $type . '/' . end($split);
                if (isset($payload['payload']['init_images'])) {
                    $split = explode('/init_images/', $payload['payload']['init_images']);
                    $payload['payload']['init_images'] = '/init_images/' . end($split);
                }
                self::$checkpointFiles[$checkpoint]['payloads'][] = $payload;
            }
        }

        return self::$checkpointFiles;
    }

    public function collectFilesByTypeAndDateTime(string $type, string $dateTime): array|null
    {
        $split = explode('_', $dateTime);
        $dateTime = $split[0] . ' ' . str_replace('-', ':', $split[1]);
        if (!isset(self::$fileData[$type][$dateTime])) {
            return null;
        }

        self::$type = $type;
        self::$dateTime = $dateTime;
        self::$filesByTypeAndDateTime = [
            'type' => $type,
            'payloads' => $this->getDataFromPayload($type, $dateTime)
        ];

        return self::$filesByTypeAndDateTime;
    }

    public function collectUsedCheckpoints(): array
    {
        if (null === self::$payloads) {
            $this->collectPayloads();
        }
        if (!count(self::$payloads)) {
            return [];
        }

        $checkpoints = [];
        foreach (self::$payloads as $payload) {
            if (isset($payload['payload']['override_settings']['sd_model_checkpoint'])) {
                if (!in_array($payload['payload']['override_settings']['sd_model_checkpoint'], $checkpoints)) {
                    $checkpoints[] = $payload['payload']['override_settings']['sd_model_checkpoint'];
                }
            }
        }
        sort($checkpoints);

        return $checkpoints;
    }

    public function collectFilesByCheckpoint(string $checkpoint): array|null
    {
        $filesByCheckpoint = [];
        $payloads = $this->collectPayloads();
        foreach ($payloads as $payload) {
            if (isset($payload['payload']['override_settings']['sd_model_checkpoint'])) {
                if ($payload['payload']['override_settings']['sd_model_checkpoint'] === $checkpoint) {
                    if (isset($payload['file'])) {
                        $split = explode('/outputs/', $payload['file']);
                        $payload['file'] = '/outputs/' . end($split);
                    }
                    if (isset($payload['payload']['init_images'])) {
                        $split = explode('/init_images/', $payload['payload']['init_images']);
                        $payload['payload']['init_images'] = '/init_images/' . end($split);
                    }
                    $filesByCheckpoint[] = $payload;
                }
            }
        }

        if (count($filesByCheckpoint)) {
            self::$checkpoint = $checkpoint;
            self::$filesByCheckpoint = [
                'type' => $checkpoint,
                'payloads' => $filesByCheckpoint
            ];
        } else {
            self::$checkpoint = null;
            self::$filesByCheckpoint = null;
        }

        return self::$filesByCheckpoint;
    }

    public function getFileData(): array|null
    {
        return self::$fileData;
    }

    public function getFilesByType(string $type): array
    {
        return self::$filesByType;
    }

    public function getCheckpointFiles(): array
    {
        return self::$checkpointFiles;
    }

    public function getFilesByTypeAndDateTime(): array|null
    {
        return self::$filesByTypeAndDateTime;
    }

    public function getType(): string|null
    {
        return self::$type;
    }

    public function getDateTime(): string|null
    {
        return self::$dateTime;
    }

    public function getFilesByCheckpoint(): array|null
    {
        return self::$filesByCheckpoint;
    }

    public function getCheckpoint(): string|null
    {
        return self::$checkpoint;
    }
}