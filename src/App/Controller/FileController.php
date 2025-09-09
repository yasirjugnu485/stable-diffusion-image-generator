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

use App\Interface\Interface\FileInterface;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileController implements FileInterface
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
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->handleActions();
        $this->collectFiles();
    }

    /**
     * Handle actions
     *
     * @return void
     */
    private function handleActions(): void
    {
        if (null === self::$fileData) {
            if (isset($_POST['action']) && $_POST['action'] === 'deleteImage') {
                $this->deleteImage();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'deleteByTypeAndDateTime') {
                $this->deleteByTypeAndDateTime();
            }
        }
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
                if (str_ends_with($fileOrArray, 'data.json')) {
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
                    if (file_exists(ROOT_DIR . 'outputs/' . $type . '/' . $dateTime . '/data.json')) {
                        self::$payloads = array_merge(self::$payloads, json_decode(
                            file_get_contents(
                                ROOT_DIR . 'outputs/' . $type . '/' . $dateTime . '/data.json'),
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
            file_get_contents(ROOT_DIR . 'outputs/' . $type . '/' . $key . '/data.json'), true
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
        if (!isset(self::$fileData[$type])) {
            return [];
        }

        $filesByType['payloads'] = [];
        foreach (self::$payloads as $payload) {
            if ($payload['mode'] === $type) {
                $split = explode('/outputs/' . $type . '/', $payload['file']);
                $payload['file'] = '/outputs/' . $type . '/' . end($split);
                if (isset($payload['payload']['init_images'])) {
                    $split = explode('/init_images/', $payload['payload']['init_images']);
                    $payload['payload']['init_images'] = '/init_images/' . end($split);
                }
                $filesByType['payloads'][] = $payload;
                if (count($filesByType) >= $limit) {
                    break;
                }
            }
        }

        return $filesByType;
    }

    /**
     * Collect files by type and date time
     *
     * @param string $type Type
     * @param string $dateTime Date time
     * @return array
     */
    public function collectFilesByTypeAndDateTime(string $type, string $dateTime): array
    {
        if (!isset(self::$fileData[$type][$dateTime])) {
            return [];
        }

        return [
            'type' => $type,
            'payloads' => $this->getDataFromPayload($type, $dateTime)
        ];
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
            if (isset($payload['data']['override_settings']['sd_model_checkpoint'])) {
                if (!in_array($payload['data']['override_settings']['sd_model_checkpoint'], $checkpoints)) {
                    $checkpoints[] = $payload['data']['override_settings']['sd_model_checkpoint'];
                }
            }
        }
        sort($checkpoints);

        return $checkpoints;
    }

    /**
     * Collect set of checkpoint files
     *
     * @param int $limit Limit per type
     * @return array
     */
    public function collectCheckpointFiles(int $limit = 10): array
    {
        $checkpointFiles = [];

        foreach (self::$payloads as $payload) {
            if (isset($payload['data']['override_settings']['sd_model_checkpoint'])) {
                $checkpoint = $payload['data']['override_settings']['sd_model_checkpoint'];
                $type = $payload['mode'];
                if (!isset($checkpointFiles[$checkpoint])) {
                    $checkpointFiles[$checkpoint] = [];
                }
                if (!isset($checkpointFiles[$checkpoint]['payloads'])) {
                    $checkpointFiles[$checkpoint]['payloads'] = [];
                }
                if (count($checkpointFiles[$checkpoint]['payloads']) >= $limit) {
                    continue;
                }
                $split = explode('/outputs/' . $type . '/', $payload['file']);
                $payload['file'] = '/outputs/' . $type . '/' . end($split);
                if (isset($payload['data']['init_images'])) {
                    $split = explode('/init_images/', $payload['data']['init_images']);
                    $payload['data']['init_images'] = '/init_images/' . end($split);
                }
                $checkpointFiles[$checkpoint]['payloads'][] = $payload;
            }
        }

        return $checkpointFiles;
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
            if (isset($payload['data']['override_settings']['sd_model_checkpoint'])) {
                if ($payload['data']['override_settings']['sd_model_checkpoint'] === $checkpoint) {
                    if (isset($payload['file'])) {
                        $split = explode('/outputs/', $payload['file']);
                        $payload['file'] = '/outputs/' . end($split);
                    }
                    if (isset($payload['data']['init_images'])) {
                        $split = explode('/init_images/', $payload['data']['init_images']);
                        $payload['data']['init_images'] = '/init_images/' . end($split);
                    }
                    $filesByCheckpoint[] = $payload;
                }
            }
        }

        return [
            'type' => $checkpoint,
            'payloads' => $filesByCheckpoint
        ];
    }

    /**
     * Delete image
     *
     * @return void
     */
    private function deleteImage(): void
    {
        if (!isset($_POST['image'])) {
            die();
        }

        $image = $_POST['image'];
        $trimmed = trim($image, '/');
        $split = explode('/', $trimmed);
        $type = $split[1];
        $dateTime = $split[2];

        if (!file_exists(ROOT_DIR . 'outputs/' . $type . '/' . $dateTime . '/data.json')) {
            die();
        }

        $payloads = json_decode(
            file_get_contents(ROOT_DIR . 'outputs/' . $type . '/' . $dateTime . '/data.json'),
            true
        );

        foreach ($payloads as $index => $payload) {
            $split = explode('/outputs/', $payload['file']);
            $file = '/outputs/' . end($split);
            if ($file === $image) {
                if (file_exists($payload['file'])) {
                    unlink($payload['file']);
                }
                unset($payloads[$index]);
                if (!count($payloads)) {
                    $toolController = new ToolController();
                    $toolController->deleteDirectory(ROOT_DIR . 'outputs/' . $type . '/' . $dateTime);
                    die();
                }
                break;
            }
        }

        file_put_contents(
            ROOT_DIR . 'outputs/' . $type . '/' . $dateTime . '/data.json',
            json_encode($payloads)
        );

        die();
    }

    /**
     * Delete by type and date time
     *
     * @return void
     */
    private function deleteByTypeAndDateTime(): void
    {
        if (!isset($_POST['type']) || !isset($_POST['dateTime'])) {
            new ErrorController(self::ERROR_DELETE_BY_TYPE_AND_DIRECTORY);
            $this->redirect();
        }

        $type = $_POST['type'];
        $split = explode('_', $_POST['dateTime']);
        $dateTime = $split[0] . ' ' . str_replace('-', ':', $split[1]);

        $this->collectFiles();
        if (!isset(self::$fileData[$type][$dateTime])) {
            new ErrorController(self::ERROR_DELETE_BY_TYPE_AND_DIRECTORY);
            $this->redirect();
        }

        $toolController = new ToolController();
        $toolController->deleteDirectory(ROOT_DIR . 'outputs/' . $type . '/' . $dateTime);

        new SuccessController(self::SUCCESS_DELETE_BY_TYPE_AND_DIRECTORY);
        $this->redirect(true);
    }

    /**
     * Redirect
     *
     * @param bool $home Redirect to home page
     * @return void
     */
    public function redirect(bool $home = false): void
    {
        if ($home) {
            $httpReferer = $_SERVER['HTTP_REFERER'];
            if (str_starts_with($httpReferer, 'http://')) {
                $httpReferer = str_replace('http://', '', $httpReferer);
                $split = explode('/', $httpReferer);
                $url = 'http://' . $split[0];
            } elseif (str_starts_with($httpReferer, 'https://')) {
                $httpReferer = str_replace('https://', '', $httpReferer);
                $split = explode('/', $httpReferer);
                $url = 'https://' . $split[0];
            }
            header('Location: ' . $url);
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        exit();
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
}