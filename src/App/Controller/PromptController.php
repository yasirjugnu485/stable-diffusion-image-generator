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

use App\Interface\PromptInterface;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PromptController implements PromptInterface
{
    /**
     * Prompt data
     *
     * @var array|null
     */
    public static array|null $promptData = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->handleActions();
        $this->collectPrompts();
    }

    /**
     * Handle actions
     *
     * @return void
     */
    private function handleActions(): void
    {
        if (null === self::$promptData) {
            if (isset($_POST['action']) && $_POST['action'] === 'addPromptMergerDirectory') {
                $this->addPromptMergerDirectory();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'renamePromptMergerDirectory') {
                $this->renamePromptMergerDirectory();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'deletePromptMergerDirectory') {
                $this->deletePromptMergerDirectory();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'addPromptMergerFile') {
                $this->addPromptMergerFile();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'editPromptMergerFiles') {
                $this->editPromptMergerFiles();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'deletePromptMergerFile') {
                $this->deletePromptMergerFile();
            }
        }
    }

    /**
     * Collect all prompts
     *
     * @return void
     */
    private function collectPrompts(): void
    {
        if (null === self::$promptData) {
            $promptData = $this->collectFiles(ROOT_DIR . 'prompt/');
            if (count($promptData)) {
                foreach ($promptData as $prompt => $files) {
                    if (count($files)) {
                        sort($promptData[$prompt]);
                    } else {
                        unset($promptData[$prompt]);
                    }
                }
                self::$promptData = $promptData;
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
                if (!str_ends_with($item->getPathname(), '.txt')) {
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
     * Add prompt merger directory
     *
     * @return void
     */
    protected function addPromptMergerDirectory(): void
    {
        $directory = trim($_POST['directory']);
        $directory = str_replace(' ', '_', $directory);
        $match = preg_match('/^[a-zA-Z0-9_-]+$/', $directory);
        if (!$match) {
            new ErrorController(self::ERROR_PROMPT_MERGER_DIRECTORY_WRONG_NAME);
            new RedirectController();
        } elseif (is_dir(ROOT_DIR . 'prompt/' . $directory) ||
            is_file(ROOT_DIR . 'prompt/' . $directory)) {
            new ErrorController(self::ERROR_PROMPT_MERGER_DIRECTORY_EXISTS);
            new RedirectController();
        }

        mkdir(ROOT_DIR . 'prompt/' . $directory, 0777, true);
        file_put_contents(ROOT_DIR . 'prompt/' . $directory . '/001_style.txt', '');

        new SuccessController(self::SUCCESS_PROMPT_MERGER_DIRECTORY_CREATED);
        new RedirectController();
    }

    /**
     * Rename prompt merger directory
     *
     * @return void
     */
    protected function renamePromptMergerDirectory(): void
    {
        $directory = trim($_POST['directory']);
        $directory = str_replace(' ', '_', $directory);
        $match = preg_match('/^[a-zA-Z0-9_-]+$/', $directory);
        if (!$match) {
            new ErrorController(self::ERROR_RENAME_PROMPT_MERGER_DIRECTORY);
            new RedirectController();
        }

        $toolController = new ToolController();
        $url = rtrim($toolController->getCurrentUrl(), '/');
        $split = explode('/', $url);
        $currentDirectory = end($split);
        if ($currentDirectory === $directory) {
            new SuccessController(self::SUCCESS_RENAME_PROMPT_MERGER_DIRECTORY);
            new RedirectController();
        } elseif (is_dir(ROOT_DIR . 'prompt/' . $directory) ||
            is_file(ROOT_DIR . 'prompt/' . $directory)) {
            new ErrorController(self::ERROR_PROMPT_MERGER_DIRECTORY_EXISTS);
            new RedirectController();
        }

        rename(ROOT_DIR . 'prompt/' . $currentDirectory, ROOT_DIR . 'prompt/' . $directory);

        new SuccessController(self::SUCCESS_PROMPT_MERGER_DIRECTORY_CREATED);
        new RedirectController('/prompt-merger/' . $directory);
    }

    /**
     * Delete prompt merger directory
     *
     * @return void
     */
    private function deletePromptMergerDirectory(): void
    {
        if (!isset($_POST['directory'])) {
            new ErrorController(self::ERROR_DELETE_PROMPT_MERGER_DIRECTORY);
            new RedirectController();
        }

        $directory = $_POST['directory'];
        $this->collectPrompts();
        if (!isset(self::$promptData[$directory])) {
            new ErrorController(self::ERROR_DELETE_PROMPT_MERGER_DIRECTORY);
            new RedirectController();
        }

        if (!is_dir(ROOT_DIR . 'prompt/' . $directory)) {
            new ErrorController(self::ERROR_DELETE_PROMPT_MERGER_DIRECTORY);
            new RedirectController();
        }

        $toolController = new ToolController();
        $toolController->deleteDirectory(ROOT_DIR . 'prompt/' . $directory);

        new SuccessController(self::SUCCESS_DELETE_PROMPT_MERGER_DIRECTORY);
        new RedirectController('/prompt-merger');
    }

    /**
     * Add prompt merger file
     *
     * @return void
     */
    private function addPromptMergerFile(): void
    {
        if (!isset($_POST['directory']) || !isset($_POST['name'])) {
            new ErrorController(self::ERROR_ADD_PROMPT_MERGER_FILE);
            new RedirectController();
        }

        $directory = trim($_POST['directory']);
        $name = trim($_POST['name']);
        $name = str_replace(' ', '_', $name);
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $name)) {
            new ErrorController(self::ERROR_PROMPT_MERGER_FILE_WRONG_NAME);
            new RedirectController();
        }

        $this->collectPrompts();
        if (!isset(self::$promptData[$directory])) {
            new ErrorController(self::ERROR_ADD_PROMPT_MERGER_FILE);
            new RedirectController();
        } elseif (file_exists(ROOT_DIR . 'prompt/' . $directory . '/' . $name . '.txt')) {
            new ErrorController(self::ERROR_PROMPT_MERGER_FILE_EXISTS);
            new RedirectController();
        }

        file_put_contents(ROOT_DIR . 'prompt/' . $directory . '/' . $name . '.txt', '');

        new SuccessController(self::SUCCESS_ADD_PROMPT_MERGER_FILE);
        new RedirectController();
    }

    /**
     * Edit prompt merger files
     *
     * @return void
     */
    protected function editPromptMergerFiles(): void
    {
        if (!isset($_POST['directory']) || !isset($_POST['name']) || !isset($_POST['content'])) {
            new ErrorController(self::ERROR_SAVE_PROMPT_MERGER_FILES);
            new RedirectController();
        }

        $directory = trim($_POST['directory']);
        $name = $_POST['name'];
        $content = $_POST['content'];
        foreach ($name as $index => $value) {
            unset ($name[$index]);
            $value = trim($value);
            $value = str_replace(' ', '_', $value);
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
                new ErrorController(self::ERROR_PROMPT_MERGER_FILE_WRONG_NAME);
                new RedirectController();
            }
            $name[$index . '.txt'] = $value . '.txt';
        }
        foreach ($content as $index => $value) {
            unset($content[$index]);
            $content[$index . '.txt'] = $value;
        }

        $this->collectPrompts();
        if (!isset(self::$promptData[$directory])) {
            new ErrorController(self::ERROR_SAVE_PROMPT_MERGER_FILES);
            new RedirectController();
        }

        foreach (self::$promptData[$directory] as $file) {
            if (isset($name[$file])) {
                unlink(ROOT_DIR . 'prompt/' . $directory . '/' . $file);
                file_put_contents(ROOT_DIR . 'prompt/' . $directory . '/' . $name[$file],
                    preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content[$file]));
            }
        }

        new SuccessController(self::SUCCESS_SAVE_PROMPT_MERGER_FILES);
        new RedirectController();
    }

    /**
     * Delete prompt merger file
     *
     * @return void
     */
    private function deletePromptMergerFile(): void
    {
        if (!isset($_POST['directory']) || !isset($_POST['file'])) {
            new ErrorController(self::ERROR_DELETE_PROMPT_MERGER_FILE);
            new RedirectController();
        }

        $directory = $_POST['directory'];
        $file = $_POST['file'];

        $this->collectPrompts();
        if (!isset(self::$promptData[$directory])) {
            new ErrorController(self::ERROR_DELETE_PROMPT_MERGER_FILE);
        }

        if (!file_exists(ROOT_DIR . 'prompt/' . $directory . '/' . $file . '.txt')) {
            new ErrorController(self::ERROR_DELETE_PROMPT_MERGER_FILE);
        }

        unlink(ROOT_DIR . 'prompt/' . $directory . '/' . $file . '.txt');

        new SuccessController(self::SUCCESS_DELETE_PROMPT_MERGER_FILE);
        new RedirectController();
    }

    /**
     * Prompt directory exists
     *
     * @param string $promptDirectory Prompt directory
     * @return bool
     */
    public function promptDirectoryExists(string $promptDirectory): bool
    {
        return isset(self::$promptData[$promptDirectory]);
    }

    /**
     * Get prompt files
     *
     * @param string $promptDirectory Prompt directory
     * @return array
     */
    public function getPromptFiles(string $promptDirectory): array
    {
       if (!isset(self::$promptData[$promptDirectory])) {
            new RedirectController('/prompt-merger');
       }

       $files = [];
       foreach (self::$promptData[$promptDirectory] as $file) {
           $files[] = [
               'name' => str_replace('.txt', '', $file),
               'content' => file_get_contents(ROOT_DIR . 'prompt/' . $promptDirectory . '/' . $file)
           ];
       }

       return $files;
    }

    /**
     * Get prompt directories
     *
     * @return array
     */
    public function getPromptDirectories(): array
    {
        return array_keys(self::$promptData);
    }

    /**
     * Get prompt data
     *
     * @return array|null
     */
    public function getPromptData(): array|null
    {
        return self::$promptData;
    }
}