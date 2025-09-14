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

class FileController implements FileInterface
{
    /**
     * File data
     *
     * @var array|null
     */
    public static array|null $fileData = null;

    /**
     * Data files
     *
     * @var array|null
     */
    public static array|null $dataFiles = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->handleActions();
        $this->collectFileData();
        $this->collectDataFiles();
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
            } elseif (isset($_POST['action']) && $_POST['action'] === 'deleteDirectory') {
                $this->deleteDirectory();
            }
        }
    }

    /**
     * Collect file data
     *
     * @return void
     */
    private function collectFileData(): void
    {
        if (self::$dataFiles !== null) {
            return;
        }

        $toolController = new ToolController();
        $fileData = $toolController->collectDataFiles(ROOT_DIR . 'outputs/');
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

    /**
     * Collect data files
     *
     * @return void
     */
    private function collectDataFiles(): void
    {
        if (self::$dataFiles !== null) {
            return;
        }

        self::$dataFiles = [];
        foreach (self::$fileData as $type => $typeData) {
            foreach ($typeData as $dateTime => $unused) {
                if (file_exists(ROOT_DIR . 'outputs/' . $type . '/' . $dateTime . '/data.json')) {
                    $data = json_decode(
                        file_get_contents(ROOT_DIR . 'outputs/' . $type . '/' . $dateTime . '/data.json'),
                        true
                    );
                    if (!isset(self::$dataFiles[$type])) {
                        self::$dataFiles[$type] = [];
                    }
                    self::$dataFiles[$type] = array_merge(self::$dataFiles[$type], $data);
                }
            }
        }
    }

    /**
     * Get last generated images
     *
     * @return array
     */
    public function getLastGeneratedImages(): array
    {
        $toolsController = new ToolController();

        $newest = 0;
        $targetType = false;
        $targetKey = false;
        foreach (self::$fileData['txt2img'] as $key => $file) {
            if (!$toolsController->containsDataFiles(self::$fileData['txt2img'][$key])) {
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
            if (!$toolsController->containsDataFiles(self::$fileData['img2img'][$key])) {
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
            if (!$toolsController->containsDataFiles(self::$fileData['loop'][$key])) {
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
            $dataFile = ROOT_DIR . 'outputs/' . $targetType . '/' . $targetKey . '/data.json';
            $imagesFromDataFile =  $toolsController->loadImagesFromDataFile($dataFile);
            return array_reverse($imagesFromDataFile);
        }

        return [];
    }

    /**
     * Get images by type
     *
     * @param string $type Type
     * @param int $limit Limit
     * @return array
     */
    public function getImagesByType(string $type, int $limit = 1000): array
    {
        if (!isset(self::$dataFiles[$type])) {
            new RedirectController('/');
        }

        $images = [];
        foreach (self::$dataFiles[$type] as $dataFile) {
                $dataFile['file'] = str_replace(ROOT_DIR, '/', $dataFile['file']);
                if (isset($dataFile['data']['init_images'])) {
                    $dataFile['data']['init_images'] =
                        str_replace(ROOT_DIR, '/', $dataFile['data']['init_images']);
                }
                $images[] = $dataFile;
                if (count($images) >= $limit) {
                    break;
                }
            }

        return array_reverse($images);
    }

    /**
     * Get images by type and date time
     *
     * @param string $type Type
     * @param string $dateTime Date time
     * @return array
     */
    public function getImagesByTypeAndDateTime(string $type, string $dateTime): array
    {
        if (!isset(self::$fileData[$type][$dateTime])) {
            new RedirectController('/' . $type);
        }

        $toolsController = new ToolController();
        $dataFile = ROOT_DIR . 'outputs/' . $type . '/' . $dateTime . '/data.json';
        return array_reverse($toolsController->loadImagesFromDataFile($dataFile));
    }

    /**
     * Get images by checkpoints
     *
     * @param int $limit Limit per type
     * @return array
     */
    public function getImagesByCheckpoints(int $limit = 10): array
    {
        $checkpoints = [];
        foreach (self::$dataFiles as $type => $images) {
            foreach ($images as $image) {
                if (isset($image['data']['override_settings']['sd_model_checkpoint'])) {
                    $checkpoint = $image['data']['override_settings']['sd_model_checkpoint'];
                    if (!isset($checkpoints[$checkpoint])) {
                        $checkpoints[$checkpoint] = [];
                    }
                    if (count($checkpoints[$checkpoint]) >= $limit) {
                        continue;
                    }
                    $image['file'] = str_replace(ROOT_DIR, '/', $image['file']);
                    if (isset($image['data']['init_images'])) {
                        $images['data']['init_images'] =
                            str_replace(ROOT_DIR, '/', $image['data']['init_images']);
                    }
                    $checkpoints[$checkpoint][] = $image;
                }
            }
        }

        return $checkpoints;
    }

    /**
     * Get images by checkpoint
     *
     * @param string $checkpoint Checkpoint
     * @param int $limit Limit
     * @return array|null
     */
    public function getImagesByCheckpoint(string $checkpoint, int $limit = 1000): array|null
    {
        $images = [];
        foreach (self::$dataFiles as $type => $dataFile) {
            foreach ($dataFile as $image) {
                if ((isset($image['data']['override_settings']['sd_model_checkpoint']) &&
                    $image['data']['override_settings']['sd_model_checkpoint'] === $checkpoint) ||
                        (isset($image['data']['refiner_checkpoint']) &&
                        $image['data']['refiner_checkpoint'] === $checkpoint))
                {
                    $image['file'] = str_replace(ROOT_DIR, '/', $image['file']);
                    if (isset($image['data']['init_images'])) {
                        $image['data']['init_images'] =
                            str_replace(ROOT_DIR, '/', $image['data']['init_images']);
                    }
                    $images[] = $image;
                    if (count($images) >= $limit) {
                        break 2;
                    }
                } elseif (isset($image['data']['refiner_checkpoint']) &&
                    $image['data']['refiner_checkpoint'] === $checkpoint) {
                    $image['file'] = str_replace(ROOT_DIR, '/', $image['file']);
                    if (isset($image['data']['init_images'])) {
                        $image['data']['init_images'] =
                            str_replace(ROOT_DIR, '/', $image['data']['init_images']);
                    }
                    $images[] = $image;
                }
            }
        }
        if (!count($images)) {
            new RedirectController('/checkpoints');
        }

        return $images;
    }

    /**
     * Get used and used refiner checkpoints
     *
     * @return array
     */
    public function getCheckpoints(): array
    {
        $checkpoints = $this->getUsedCheckpoints();
        $usedRefinedCheckpoints = $this->getUsedRefinerCheckpoints();
        foreach ($usedRefinedCheckpoints as $usedRefinedCheckpoint) {
            if (!in_array($usedRefinedCheckpoint, $checkpoints)) {
                $checkpoints[] = $usedRefinedCheckpoint;
            }
        }
        sort($checkpoints);

        return $checkpoints;
    }

    /**
     * Get used checkpoints
     *
     * @return array
     */
    public function getUsedCheckpoints(): array
    {
        if (self::$dataFiles) {
            $this->collectDataFiles();
        }

        $toolsController = new ToolController();
        $checkpoints = [];
        foreach (self::$dataFiles as $type => $dataFile) {
            $checkpoints =
                array_merge($checkpoints, $toolsController->collectCheckpointsFromDataFiles($dataFile));
        }
        sort($checkpoints);

        return $checkpoints;
    }

    /**
     * Get used refiner checkpoints
     *
     * @return array
     */
    public function getUsedRefinerCheckpoints(): array
    {
        if (self::$dataFiles) {
            $this->collectDataFiles();
        }
        $toolsController = new ToolController();
        $checkpoints = [];
        foreach (self::$dataFiles as $type => $dataFile) {
            $checkpoints =
                array_merge($checkpoints, $toolsController->collectRefinerCheckpointsFromDataFiles($dataFile));
        }
        sort($checkpoints);

        return $checkpoints;
    }

    /**
     * Get used modes
     *
     * @return array
     */
    public function getUsedModes(): array
    {
        if (self::$fileData) {
            $this->collectFileData();
        }

        $usedModes = [];
        if (isset(self::$fileData['txt2img']) && count(self::$fileData['txt2img'])) {
            $usedModes[] = 'txt2img';
        }
        if (isset(self::$fileData['img2img']) && count(self::$fileData['img2img'])) {
            $usedModes[] = 'img2img';
        }
        if (isset(self::$fileData['loop']) && count(self::$fileData['loop'])) {
            $usedModes[] = 'loop';
        }

        return $usedModes;
    }

    /**
     * Get type directories
     *
     * @param string $type Type
     * @return array
     */
    public function getTypeDirectories(string $type): array
    {
        if (self::$fileData) {
            $this->collectFileData();
        }

        return isset(self::$fileData[$type]) ? array_keys(self::$fileData[$type]) : [];
    }

    /**
     * Delete image
     *
     * @return void
     */
    private function deleteImage(): void
    {
        if (!isset($_POST['image'])) {
            new JsonResponseController([
                'success' => false,
                'message' => self::ERROR_DELETE_IMAGE
            ]);
        }

        $image = ltrim($_POST['image'], '/');
        $splitImage = explode('/', $image);
        $splitImage[count($splitImage) - 1] = 'data.json';
        $dataFile = ROOT_DIR . implode('/' , $splitImage);
        if (!file_exists($dataFile)) {
            new JsonResponseController([
                'success' => false,
                'message' => self::ERROR_DELETE_IMAGE
            ]);
        }
        $data = json_decode(file_get_contents($dataFile), true);

        foreach ($data as $index => $entry) {
            if (str_replace(ROOT_DIR, '', $entry['file']) === $image) {
                unset($data[$index]);
                file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                if (file_exists(ROOT_DIR . $image)) {
                    unlink(ROOT_DIR . $image);
                }
                break;
            }
        }

        new JsonResponseController([
            'success' => true,
            'message' => self::SUCCESS_DELETE_IMAGE
        ]);
    }

    /**
     * Delete directory
     *
     * @return void
     */
    private function deleteDirectory(): void
    {
        if (!isset($_POST['type']) || !isset($_POST['dateTime'])) {
            new ErrorController(self::ERROR_DELETE_BY_TYPE_AND_DIRECTORY);
            new RedirectController();
        }

        $type = $_POST['type'];
        $dateTime = $_POST['dateTime'];

        $this->collectFileData();
        if (!isset(self::$fileData[$type][$dateTime])) {
            new ErrorController(self::ERROR_DELETE_BY_TYPE_AND_DIRECTORY);
            new RedirectController();
        }

        $toolController = new ToolController();
        $toolController->deleteDirectory(ROOT_DIR . 'outputs/' . $type . '/' . $dateTime);

        new SuccessController(self::SUCCESS_DELETE_BY_TYPE_AND_DIRECTORY);
        new RedirectController('/' . $type);
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