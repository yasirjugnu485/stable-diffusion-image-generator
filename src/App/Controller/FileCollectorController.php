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
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileCollectorController
{
    /**
     * File data
     *
     * @var array|null
     */
    public static array|null $fileData = null;

    /**
     * Payload files
     *
     * @var array|null
     */
    public static array|null $payloads = null;

    /**
     * Files collected by type
     *
     * @var array|null
     */
    public static array|null $filesByType = null;

    /**
     * File set collected by checkpoint
     *
     * @var array|null
     */
    public static array|null $checkpointFiles = null;

    /**
     * Files collected by type and date time
     *
     * @var array|null
     */
    public static array|null $filesByTypeAndDateTime = null;

    /**
     * Type from last collected by type or by type and date time
     *
     * @var string|null
     */
    public static string|null $type = null;

    /**
     * Date time from last collected by type and date time
     *
     * @var string|null
     */
    public static string|null $dateTime = null;

    /**
     * Files collected by checkpoint
     *
     * @var array|null
     */
    public static array|null $filesByCheckpoint = null;

    /**
     * Checkpoint from last collected by checkpoint
     *
     * @var string|null
     */
    public static string|null $checkpoint = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->collectFiles();
    }

    /**
     * Collect all files and payloads
     *
     * @return void
     */
    private function collectFiles(): void
    {
        if (null === self::$fileData) {
            $fileData = $this->collectFileList(ROOT_DIR . 'outputs/');
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

    /**
     * Collect files from directory
     *
     * @param string $directory Directory
     * @return array
     */
    private function collectFileList(string $directory): array
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
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

        return $this->parsePathsOfFiles($result);
    }

    /**
     * Parse path of files
     *
     * @param array $array Collected file list
     * @return array|mixed
     */
    private function parsePathsOfFiles(array $array)
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
     * Get last generated files
     *
     * @return array
     */
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

    /**
     * Check if array contains files
     *
     * @param array $array Files
     * @return bool
     */
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

    /**
     * Collect payloads
     *
     * @return array
     */
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

    /**
     * Get data from payloads by type and key
     *
     * @param string $type Type
     * @param string $key Key
     * @return array
     */
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

    /**
     * Collect files by type
     *
     * @param string $type Type
     * @param int $limit Limit
     * @return array
     */
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

    /**
     * Collect set of checkpoint files
     *
     * @param int $limit Limit per type
     * @return array
     */
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

    /**
     * Collect files by type and date time
     *
     * @param string $type Type
     * @param string $dateTime Date time
     * @return array|null
     */
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

    /**
     * Collect used checkpoints
     *
     * @return array
     */
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

    /**
     * Collect files by checkpoint
     *
     * @param string $checkpoint Checkpoint
     * @return array|null
     */
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

    /**
     * Get file data
     *
     * @return array|null
     */
    public function getFileData(): array|null
    {
        return self::$fileData;
    }

    /**
     * Get collected files by type
     *
     * @param string $type
     * @return array
     */
    public function getFilesByType(string $type): array
    {
        return self::$filesByType;
    }

    /**
     * Get file set collected by checkpoint
     *
     * @return array
     */
    public function getCheckpointFiles(): array
    {
        return self::$checkpointFiles;
    }

    /**
     * Get files collected by type and date time
     *
     * @return array|null
     */
    public function getFilesByTypeAndDateTime(): array|null
    {
        return self::$filesByTypeAndDateTime;
    }

    /**
     * Get type from last collected by type or by type and date time
     *
     * @return string|null
     */
    public function getType(): string|null
    {
        return self::$type;
    }

    /**
     * Get date time from last collected by type and date time
     *
     * @return string|null
     */
    public function getDateTime(): string|null
    {
        return self::$dateTime;
    }

    /**
     * Get files collected by checkpoint
     *
     * @return array|null
     */
    public function getFilesByCheckpoint(): array|null
    {
        return self::$filesByCheckpoint;
    }

    /**
     * Get checkpoint from last collected by checkpoint
     *
     * @return string|null
     */
    public function getCheckpoint(): string|null
    {
        return self::$checkpoint;
    }
}