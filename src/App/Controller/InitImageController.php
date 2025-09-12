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

use App\Interface\Interface\InitImagesInterface;
use FilesystemIterator;
use Random\RandomException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class InitImageController implements InitImagesInterface
{
    /**
     * Initialize images data
     *
     * @var array|null
     */
    public static array|null $initImagesData = null;

    /**
     * Constructor
     *
     * @return void
     * @throws RandomException
     */
    public function __construct()
    {
        $this->handleActions();
        $this->collectInitImages();
    }

    /**
     * Handle actions
     *
     * @return void
     * @throws RandomException
     */
    private function handleActions(): void
    {
        if (null === self::$initImagesData) {
            if (isset($_POST['action']) && $_POST['action'] === 'addInitImagesDirectory') {
                $this->addInitImagesDirectory();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'editInitImagesImages') {
                $this->editInitImagesImages();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'addInitImagesImage') {
                $this->addInitImagesImage();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'deleteInitImagesImage') {
                $this->deleteInitImagesImage();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'deleteInitImagesDirectory') {
                $this->deleteInitImagesDirectory();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'copyEntry') {
                $this->copyEntry();
            }
        }
    }

    /**
     * Collect all initialize images
     *
     * @return void
     */
    private function collectInitImages(): void
    {
        if (null === self::$initImagesData) {
            $initImagesData = $this->collectFiles(ROOT_DIR . 'init_images/');
            if (count($initImagesData)) {
                foreach ($initImagesData as $directory => $files) {
                    if (count($files)) {
                        sort($initImagesData[$directory]);
                    }
                }
                self::$initImagesData = $initImagesData;
            }
        }
    }

    /**
     * Collect files from directory
     *
     * @param string $directory Directory
     * @return array
     */
    public function collectFiles(string $directory): array
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $result = [];
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $pathname = explode('/', $item->getPathname());
                $result[end($pathname)] = [];
            }
        }
        foreach ($iterator as $item) {
            if ($item->isFile()) {
                if (!str_ends_with($item->getPathname(), '.png') &&
                    !str_ends_with($item->getPathname(), '.jpg') &&
                    !str_ends_with($item->getPathname(), '.jpeg')) {
                    continue;
                }
                $pathname = explode('/', $item->getPathname());
                $prompt = $pathname[count($pathname) - 2];
                $file = end($pathname);
                if (isset($result[$prompt])) {
                    $result[$prompt][] = $file;
                }
            }
        }

        return $result;
    }

    /**
     * Add init images directory
     *
     * @return void
     */
    protected function addInitImagesDirectory(): void
    {
        $directory = $_POST['directory'];
        $match = preg_match('/^[a-zA-Z0-9_-]+$/', $directory);
        if (!$match) {
            new ErrorController(self::ERROR_INIT_IMAGES_DIRECTORY_WRONG_NAME);
            return;
        } elseif (is_dir(ROOT_DIR . 'init_images/' . $directory) ||
            is_file(ROOT_DIR . 'init_images/' . $directory)) {
            new ErrorController(self::ERROR_INIT_IMAGES_DIRECTORY_EXISTS);
            return;
        }

        mkdir(ROOT_DIR . 'init_images/' . $directory, 0777, true);

        new SuccessController(self::SUCCESS_INIT_IMAGES_DIRECTORY_CREATED);
    }

    /**
     * Edit initialize images images
     *
     * @return void
     * @throws RandomException
     */
    protected function editInitImagesImages(): void
    {
        if (!isset($_POST['directory']) || !isset($_POST['name']) || !isset($_POST['file'])) {
            new ErrorController(self::ERROR_SAVE_INITIALIZE_IMAGES_IMAGES);
            $this->redirect();
        }

        $directory = $_POST['directory'];
        $name = $_POST['name'];
        $file = $_POST['file'];
        foreach ($name as $value) {
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
                new ErrorController(self::ERROR_INIT_IMAGES_IMAGE_WRONG_NAME);
                $this->redirect();
            }
        }
        if (!$this->noDuplicates($name)) {
            new ErrorController(self::ERROR_INIT_IMAGES_NAME_DUPLICATED);
            $this->redirect();
        }

        $this->collectInitImages();
        if (!isset(self::$initImagesData[$directory])) {
            new ErrorController(self::ERROR_SAVE_INITIALIZE_IMAGES_IMAGES);
            $this->redirect();
        }

        foreach ($file as $value) {
            if (!in_array($value, self::$initImagesData[$directory])) {
                new ErrorController(self::ERROR_SAVE_INITIALIZE_IMAGES_IMAGES);
                $this->redirect();
            }
        }

        $toolController = new ToolController();

        $images = [];
        foreach ($file as $index => $value) {
            $split = explode('.', $value);
            $extension = '.' . end($split);
            $tempName = $toolController->generateRandomString();
            $images[] = [
                'old_name' => str_replace($extension, '', $value),
                'temp_name' => $tempName,
                'new_name' => $name[$index],
                'extension' => $extension
            ];
            rename(ROOT_DIR . 'init_images/' . $directory . '/' . $value, ROOT_DIR . 'init_images/' . $directory . '/' . $tempName . $extension);
        }

        foreach ($images as $image) {
            rename(ROOT_DIR . 'init_images/' . $directory . '/' . $image['temp_name'] . $image['extension'], ROOT_DIR . 'init_images/' . $directory . '/' . $image['new_name'] . $image['extension']);
        }

        new SuccessController(self::SUCCESS_SAVE_INITIALIZE_IMAGES_IMAGES);

        $this->redirect();
    }

    /**
     * Add init images image
     *
     * @return void
     */
    private function addInitImagesImage(): void
    {
        if (!isset($_POST['directory']) || !isset($_POST['name']) || !isset($_FILES['image'])) {
            new ErrorController(self::ERROR_ADD_INIT_IMAGES_IMAGE);
            $this->redirect();
        }

        $directory = $_POST['directory'];
        $name = str_replace('.png', '', $_POST['name']);
        $name = str_replace('.jpg', '', $name);
        $name = str_replace('.jpeg', '', $name);
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $name)) {
            new ErrorController(self::ERROR_INIT_IMAGES_IMAGE_WRONG_NAME);
            $this->redirect();
        }

        $image = $_FILES['image'];
        if ($image['type'] === 'image/png') {
            $extension = '.png';
        } elseif ( $image['type'] === 'image/jpg' || $image['type'] === 'image/jpeg') {
            $extension = '.jpg';
        } else {
            new ErrorController(self::ERROR_INIT_IMAGES_IMAGE_WRONG_FILE);
            $this->redirect();
        }

        $this->collectInitImages();
        if (!isset(self::$initImagesData[$directory])) {
            new ErrorController(self::ERROR_ADD_INIT_IMAGES_IMAGE);
            $this->redirect();
        } elseif (file_exists(ROOT_DIR . 'init_images/' . $directory . '/' . $name . $extension)) {
            new ErrorController(self::ERROR_INIT_IMAGES_IMAGE_EXISTS);
            $this->redirect();
        }

        move_uploaded_file($image['tmp_name'], ROOT_DIR . 'init_images/' . $directory . '/' . $name . $extension);

        new SuccessController(self::SUCCESS_ADD_INIT_IMAGES_FILE);

        $this->redirect();
    }

    /**
     * Delete initialize images image
     *
     * @return void
     */
    private function deleteInitImagesImage(): void
    {
        if (!isset($_POST['directory']) || !isset($_POST['file'])) {
            new ErrorController(self::ERROR_DELETE_INITIALIZE_IMAGES_IMAGE);
            $this->redirect();
        }

        $directory = $_POST['directory'];
        $file = $_POST['file'];

        $this->collectInitImages();
        if (!isset(self::$initImagesData[$directory])) {
            new ErrorController(self::ERROR_DELETE_INITIALIZE_IMAGES_IMAGE);
        }

        if (!file_exists(ROOT_DIR . 'init_images/' . $directory . '/' . $file)) {
            new ErrorController(self::ERROR_DELETE_INITIALIZE_IMAGES_IMAGE);
        }

        unlink(ROOT_DIR . 'init_images/' . $directory . '/' . $file);

        new SuccessController(self::SUCCESS_DELETE_INITIALIZE_IMAGES_IMAGE);

        $this->redirect();
    }

    /**
     * Delete initialize images directory
     *
     * @return void
     */
    private function deleteInitImagesDirectory(): void
    {
        if (!isset($_POST['directory'])) {
            new ErrorController(self::ERROR_DELETE_INIT_IMAGES_DIRECTORY);
            $this->redirect();
        }

        $directory = $_POST['directory'];
        $this->collectInitImages();
        if (!isset(self::$initImagesData[$directory])) {
            new ErrorController(self::ERROR_DELETE_INIT_IMAGES_DIRECTORY);
            $this->redirect();
        }

        if (!is_dir(ROOT_DIR . 'init_images/' . $directory)) {
            new ErrorController(self::ERROR_DELETE_INIT_IMAGES_DIRECTORY);
            $this->redirect();
        }

        $toolController = new ToolController();
        $toolController->deleteDirectory(ROOT_DIR . 'init_images/' . $directory);

        new SuccessController(self::SUCCESS_DELETE_INIT_IMAGES_DIRECTORY);

        $this->redirect('/initialize-images');
    }

    /**
     * Check if array has no duplicates
     *
     * @param array $array Array to check for duplicates
     * @return bool
     */
    private function noDuplicates(array $array): bool
    {
        return count($array) === count(array_flip($array));
    }

    /**
     * Redirect
     *
     * @param string|null $uri URI
     * @return void
     */
    public function redirect(string|null $uri = null): void
    {
        if ($uri !== null) {
            $location = $_SERVER['HTTP_REFERER'];
            $split = explode('/initialize-images/', $location);
            $uri = $split[0] . $uri;
            header('Location: ' . $uri);
            exit();
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    /**
     * Initialize images directory exists
     *
     * @param string $initImageDirectory Initialize image directory
     * @return bool
     */
    public function initImagesDirectoryExists(string $initImageDirectory): bool
    {
        return isset(self::$initImagesData[$initImageDirectory]);
    }

    /**
     * Get initialize images
     *
     * @param string $initImageDirectory Initialize image directory
     * @return array
     */
    public function getInitImagesImages(string $initImageDirectory): array
    {
        if (!isset(self::$initImagesData[$initImageDirectory])) {
            return [];
        }

        $images = [];
        foreach (self::$initImagesData[$initImageDirectory] as $image) {
            $name = str_replace('.png', '', $image);
            $name = str_replace('.jpg', '', $name);
            $name = str_replace('.jpeg', '', $name);
            $images[] = [
                'name' => $name,
                'file' => $image,
                'url' => '/init_images/' . $initImageDirectory . '/' . $image
            ];
        }

        return $images;
    }

    /**
     * Copy entry
     *
     * @return void
     */
    public function copyEntry(): void
    {
        if (!isset($_POST['source']) || !isset($_POST['destination'])) {
            new ErrorController(self::ERROR_COPY_ENTRY);
            new RedirectController();
        }

        $source = ltrim($_POST['source'], '/');
        $destination = ltrim($_POST['destination'], '/');
        if (!str_starts_with($destination, 'init_images/')) {
            return;
        }

        $sourceFile = ROOT_DIR . $source;
        if (!file_exists($sourceFile) || !is_dir(ROOT_DIR . $destination)) {
            new ErrorController(self::ERROR_COPY_ENTRY);
            new RedirectController();
        }

        $fileSplit  = explode('/', $sourceFile);
        $fileName = end($fileSplit);

        $fileNameSplit = explode('.', $fileName);
        if (count($fileNameSplit) !== 2) {
            new ErrorController(self::ERROR_COPY_ENTRY);
            new RedirectController();
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
                copy($sourceFile, ROOT_DIR . $destination . '/' . $newName);
                break;
            }
        }

        new SuccessController(self::SUCCESS_COPY_ENTRY);
        new RedirectController();
    }

    /**
     * Get initialize images directories
     *
     * @return array
     */
    public function getInitImagesDirectories(): array
    {
        return array_keys(self::$initImagesData);
    }

    /**
     * Get initialize images data
     *
     * @return array|null
     */
    public function getInitImagesData(): array|null
    {
        return self::$initImagesData;
    }
}