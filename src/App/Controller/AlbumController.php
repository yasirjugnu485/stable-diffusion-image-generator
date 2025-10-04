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

use App\Interface\AlbumInterface;

class AlbumController implements AlbumInterface
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
        if (isset($_POST['action']) && $_POST['action'] === 'renameAlbum') {
            $this->renameAlbum();
        } elseif (isset($_POST['action']) && $_POST['action'] === 'addAlbum') {
            $this->addAlbum();
        } elseif (isset($_POST['action']) && $_POST['action'] === 'deleteAlbum') {
            $this->deleteAlbum();
        } elseif (isset($_POST['action']) && $_POST['action'] === 'copyEntry') {
            $this->copyEntry();
        }
    }

    /**
     * Collect file data
     *
     * @return void
     */
    private function collectFileData(): void
    {
        if (self::$fileData === null) {
            return;
        }

        $toolController = new ToolController();
        self::$fileData = $toolController->collectDataFiles(ROOT_DIR . 'album/');
    }

    /**
     * Collect data files
     *
     * @return void
     */
    private function collectDataFiles(): void
    {
        if (self::$fileData === null) {
            $toolController = new ToolController();
            self::$fileData = $toolController->collectDataFiles(ROOT_DIR . 'album/');
        }
    }

    /**
     * Get root directories
     *
     * @return array
     */
    public function getRootDirectories(): array
    {
        $rootDirectories = [];
        $root = array_keys(self::$fileData);
        foreach ($root as $value) {
            if (is_int($value)) {
                continue;
            } elseif (!is_dir(ROOT_DIR . 'album/' . $value)) {
                continue;
            }
            $rootDirectories[] = $value;
        }

        sort($rootDirectories);

        return $rootDirectories;
    }

    /**
     * Get album subdirectories
     *
     * @return array
     */
    public function getAlbumSubdirectories(): array
    {
        $requestUriPrefix = [];
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestIndex = explode('/', rtrim($requestUri, '/'));
        foreach ($requestIndex as  $value) {
            if (count($requestUriPrefix) || $value !== 'album') {
                $requestUriPrefix[] = $value;
            }
        }
        if (!count($requestUriPrefix)) {
            new RedirectController('/album');
        }
        unset($requestUriPrefix[0]);
        $albumDirectories = self::$fileData;
        foreach ($requestUriPrefix as $value) {
            if (array_key_exists($value, $albumDirectories)) {
                $albumDirectories = $albumDirectories[$value];
            }
        }

        $toolController = new ToolController();
        $albumDirectories = $toolController->multiKeySort($albumDirectories);
        $url = $toolController->getCurrentUrl();

        $subDirectories = [];
        foreach ($albumDirectories as $key => $value) {
            if (is_int($key)) {
                continue;
            }
            $subDirectories[] = [
                'name' => str_replace('_', ' ', $key),
                'link' => rtrim($url, '/') . '/' . $key,
            ];
        }

        return $subDirectories;
    }

    /**
     * Get album images
     *
     * @return array
     */
    public function getAlbumImages(): array
    {
        $dataFile = ROOT_DIR . trim($_SERVER['REQUEST_URI'], '/') . '/data.json';
        if (!file_exists($dataFile)) {
            if ($_SERVER['REQUEST_URI'] !== '/album') {
                new RedirectController('/album');
            } else {
                return [];
            }
        }

        return json_decode(file_get_contents($dataFile), true);
    }

    /**
     * Rename album
     *
     * @return void
     */
    private function renameAlbum(): void
    {
        if (!isset($_POST['album'])) {
            new ErrorController(self::ERROR_RENAME_ALBUM);
            new RedirectController();
        }

        $requestUri = trim($_SERVER['REQUEST_URI'], '/');
        $requestIndex = explode('/', rtrim($requestUri, '/'));
        if (!count($requestIndex)) {
            new ErrorController(self::ERROR_RENAME_ALBUM);
            new RedirectController();
        }
        $currentDirectory = implode('/', $requestIndex);
        if (!is_dir(ROOT_DIR . $currentDirectory)) {
            new ErrorController(self::ERROR_RENAME_ALBUM);
            new RedirectController();
        }

        $newName = trim($_POST['album']);
        $newName = str_replace(' ', '_', $newName);
        $match = preg_match('/^[a-zA-Z0-9_-]+$/', $newName);
        if (!$newName || !$match) {
            new ErrorController(self::ERROR_RENAME_WRONG_NAME);
            new RedirectController();
        }

        $splitCurrentDirectory = explode('/', $currentDirectory);
        array_pop($splitCurrentDirectory);
        $newDirectory = implode('/', $splitCurrentDirectory) . '/' . $newName;
        if ($currentDirectory === $newDirectory) {
            new SuccessController(self::SUCCESS_RENAME_ALBUM);
            new RedirectController();
        } elseif (is_dir($newDirectory)) {
            new ErrorController(self::ERROR_RENAME_ALBUM_EXISTS);
            new RedirectController();
        }

        $toolController = new ToolController();
        $files = $toolController->collectDataFiles(ROOT_DIR . $currentDirectory, false);
        foreach ($files as $file) {
            $entries = json_decode(file_get_contents(ROOT_DIR . $currentDirectory . $file), true);
            foreach ($entries as $index => $entry) {
                $entries[$index]['file'] = str_replace($currentDirectory, $newDirectory, $entry['file']);
            }
            file_put_contents(ROOT_DIR . $currentDirectory . $file,
                json_encode($entries), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        rename(ROOT_DIR . $currentDirectory, ROOT_DIR . $newDirectory);

        array_pop($requestIndex);
        $requestIndex[] = $newName;
        $newUri = '/' . implode('/', $requestIndex);

        new SuccessController(self::SUCCESS_RENAME_ALBUM);
        new RedirectController($newUri);
    }



    /**
     * Add album
     *
     * @return void
     */
    private function addAlbum(): void
    {
        if (!isset($_POST['album'])) {
            new ErrorController(self::ERROR_ADD_SUB_ALBUM);
            new RedirectController();
        }

        $requestUriPrefix = [];
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestIndex = explode('/', rtrim($requestUri, '/'));
        foreach ($requestIndex as  $value) {
            if (count($requestUriPrefix) || $value !== 'album') {
                $requestUriPrefix[] = $value;
            }
        }
        if (!count($requestUriPrefix)) {
            new ErrorController(self::ERROR_ADD_SUB_ALBUM);
            new RedirectController();
        }
        $slugPrefix = ROOT_DIR . implode('/', $requestUriPrefix) . '/';

        $directory = trim($_POST['album']);
        $directory = str_replace(' ', '_', $directory);
        $match = preg_match('/^[a-zA-Z0-9_-]+$/', $directory);
        if (!$directory || !$match) {
            new ErrorController(self::ERROR_ADD_SUB_ALBUM_WRONG_NAME);
            return;
        } elseif (is_dir($slugPrefix . $directory) ||
            is_file($slugPrefix . $directory)) {
            new ErrorController(self::ERROR_ADD_SUB_ALBUM_EXISTS);
            new RedirectController();
        }

        mkdir($slugPrefix . $directory, 0777, true);
        file_put_contents($slugPrefix . $directory . '/data.json', '[]');

        new SuccessController(self::SUCCESS_ADD_SUB_ALBUM);
        new RedirectController();
    }

    /**
     * Delete album
     *
     * @return void
     */
    private function deleteAlbum(): void
    {
        if (!isset($_POST['album'])) {
            new ErrorController(self::ERROR_DELETE_ALBUM);
            new RedirectController();
        }

        $directory = rtrim(ROOT_DIR, '/') . '/' . ltrim($_POST['album'], '/');
        if (!is_dir($directory)) {
            new ErrorController(self::ERROR_DELETE_ALBUM);
            new RedirectController();
        }

        $toolController = new ToolController();
        $toolController->deleteDirectory($directory);

        new SuccessController(self::SUCCESS_DELETE_ALBUM);
        new RedirectController('/album');
    }

    /**
     * Copy entry
     *
     * @return void
     */
    public function copyEntry(): void
    {
        if (!isset($_POST['source']) || !isset($_POST['destination'])) {
            new JsonResponseController([
                'success' => false,
                'message' => self::ERROR_COPY_ENTRY
            ]);
        }

        $source = ltrim($_POST['source'], '/');
        $destination = ltrim($_POST['destination'], '/');
        if (!str_starts_with($destination, 'album/')) {
            return;
        }

        $sourceSplit = explode('/', $source);
        $sourceSplit[count($sourceSplit) - 1] = 'data.json';
        $sourceFile = ROOT_DIR . implode('/', $sourceSplit);
        $destinationFile = ROOT_DIR . $destination . '/data.json';
        if (!file_exists($sourceFile) || !file_exists($destinationFile)) {
            new JsonResponseController([
                'success' => false,
                'message' => self::ERROR_COPY_ENTRY
            ]);
        }

        $sourceData = json_decode(file_get_contents($sourceFile), true);
        $destinationData = json_decode(file_get_contents($destinationFile), true);
        $entry = null;
        foreach ($sourceData as $index => $item) {
            $itemFile = str_replace(ROOT_DIR, '', $item['file']);
            if ($itemFile === $source) {
                $entry = $item;
                break;
            }
        }
        if (!file_exists(ROOT_DIR . $entry['file'])) {
            new JsonResponseController([
                'success' => false,
                'message' => self::ERROR_COPY_ENTRY
            ]);
        }

        $fileSplit = explode('/', $entry['file']);
        $fileName = $fileSplit[count($fileSplit) - 1];
        $fileNameSplit = explode('.', $fileName);
        if (count($fileNameSplit) !== 2) {
            new JsonResponseController([
                'success' => false,
                'message' => self::ERROR_COPY_ENTRY
            ]);
        }

        $name = $fileNameSplit[0];
        $extension = $fileNameSplit[1];
        $index = 0;
        while (true) {
            if ($index > 0) {
                $newName = $name . '-' . $index . '.' . $extension;
            } else {
                $newName = $name . '.' . $extension;
            }
            if (file_exists(ROOT_DIR . $destination . '/' . $newName)) {
                $index++;
            } else {
                copy(ROOT_DIR . $entry['file'], ROOT_DIR . $destination . '/' . $newName);
                break;
            }
        }

        $entry['file'] = $destination . '/' . $newName;
        array_unshift($destinationData, $entry);
        file_put_contents($destinationFile,
            json_encode($destinationData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        new JsonResponseController([
            'success' => true,
            'message' => self::SUCCESS_COPY_ENTRY
        ]);
    }

    /**
     * Get album data
     *
     * @return array|null
     */
    public function getAlbumData(): array|null
    {
        $toolController = new ToolController();

        $fileData = self::$fileData;
        return $toolController->multiKeySort($fileData);
    }
}