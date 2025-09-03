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

use App\Interface\PromptCollectorInterface;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PromptCollectorController implements PromptCollectorInterface
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
            if (isset($_POST['action']) && $_POST['action'] === 'addPromptDirectory') {
                $this->addPromptDirectory();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'editPromptFiles') {
                $this->editPromptFiles();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'addPromptFile') {
                $this->addPromptFile();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'deletePromptFile') {
                $this->deletePromptFile();
            }elseif (isset($_POST['action']) && $_POST['action'] === 'deletePromptDirectory') {
                $this->deletePromptDirectory();
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
     * Add prompt directory
     *
     * @return void
     */
    protected function addPromptDirectory(): void
    {
        $prompt = $_POST['prompt'];
        $match = preg_match('/^[a-zA-Z0-9_-]+$/', $prompt);
        if (!$match) {
            new ErrorController(self::ERROR_PROMPT_DIRECTORY_WRONG_NAME);
            $this->redirect();
        } elseif (is_dir(ROOT_DIR . 'prompt/' . $prompt) || is_file(ROOT_DIR . 'prompt/' . $prompt)) {
            new ErrorController(self::ERROR_PROMPT_DIRECTORY_EXISTS);
            $this->redirect();
        }

        mkdir(ROOT_DIR . 'prompt/' . $prompt, 0777, true);
        file_put_contents(ROOT_DIR . 'prompt/' . $prompt . '/001_style.txt', '');

        new SuccessController(self::SUCCESS_PROMPT_DIRECTORY_CREATED);

        $this->redirect();
    }

    /**
     * Edit prompt files
     *
     * @return void
     */
    protected function editPromptFiles(): void
    {
        if (!isset($_POST['prompt']) || !isset($_POST['name']) || !isset($_POST['content'])) {
            new ErrorController(self::ERROR_SAVE_PROMPT_FILES);
            $this->redirect();
        }

        $prompt = $_POST['prompt'];
        $name = $_POST['name'];
        $content = $_POST['content'];
        foreach ($name as $index => $value) {
            unset ($name[$index]);
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
                new ErrorController(self::ERROR_PROMPT_FILE_WRONG_NAME);
                $this->redirect();
            }
            $name[$index . '.txt'] = $value . '.txt';
        }
        foreach ($content as $index => $value) {
            unset($content[$index]);
            $content[$index . '.txt'] = $value;
        }

        $this->collectPrompts();
        if (!isset(self::$promptData[$prompt])) {
            new ErrorController(self::ERROR_SAVE_PROMPT_FILES);
            $this->redirect();
        }

        foreach (self::$promptData[$prompt] as $file) {
            if (isset($name[$file])) {
                unlink(ROOT_DIR . 'prompt/' . $prompt . '/' . $file);
                file_put_contents(
                    ROOT_DIR . 'prompt/' . $prompt . '/' . $name[$file],
                    preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content[$file])
                );
            }
        }

        new SuccessController(self::SUCCESS_SAVE_PROMPT_FILES);

        $this->redirect();
    }

    /**
     * Add prompt file
     *
     * @return void
     */
    private function addPromptFile(): void
    {
        if (!isset($_POST['prompt']) || !isset($_POST['file'])) {
            new ErrorController(self::ERROR_ADD_PROMPT_FILE);
            $this->redirect();
        }

        $prompt = $_POST['prompt'];
        $file = str_replace('.txt', '', $_POST['file']);
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $file)) {
            new ErrorController(self::ERROR_PROMPT_FILE_WRONG_NAME);
            $this->redirect();
        }

        $this->collectPrompts();
        if (!isset(self::$promptData[$prompt])) {
            new ErrorController(self::ERROR_ADD_PROMPT_FILE);
            $this->redirect();
        } elseif (file_exists(ROOT_DIR . 'prompt/' . $prompt . '/' . $file . '.txt')) {
            new ErrorController(self::ERROR_PROMPT_FILE_EXISTS);
            $this->redirect();
        }

        file_put_contents(ROOT_DIR . 'prompt/' . $prompt . '/' . $file . '.txt', '');

        new SuccessController(self::SUCCESS_ADD_PROMPT_FILE);

       $this->redirect();
    }

    /**
     * Delete prompt file
     *
     * @return void
     */
    private function deletePromptFile(): void
    {
        if (!isset($_POST['prompt']) || !isset($_POST['file'])) {
            new ErrorController(self::ERROR_DELETE_PROMPT_FILE);
            $this->redirect();
        }

        $prompt = $_POST['prompt'];
        $file = $_POST['file'];

        $this->collectPrompts();
        if (!isset(self::$promptData[$prompt])) {
            new ErrorController(self::ERROR_DELETE_PROMPT_FILE);
        }

        if (!file_exists(ROOT_DIR . 'prompt/' . $prompt . '/' . $file . '.txt')) {
            new ErrorController(self::ERROR_DELETE_PROMPT_FILE);
        }

        unlink(ROOT_DIR . 'prompt/' . $prompt . '/' . $file . '.txt');

        new SuccessController(self::SUCCESS_DELETE_PROMPT_FILE);

        $this->redirect();
    }

    /**
     * Delete prompt directory
     *
     * @return void
     */
    private function deletePromptDirectory(): void
    {
        if (!isset($_POST['prompt'])) {
            new ErrorController(self::ERROR_DELETE_PROMPT_DIRECTORY);
            $this->redirect();
        }

        $prompt = $_POST['prompt'];
        $this->collectPrompts();
        if (!isset(self::$promptData[$prompt])) {
            new ErrorController(self::ERROR_DELETE_PROMPT_DIRECTORY);
            $this->redirect();
        }

        if (!is_dir(ROOT_DIR . 'prompt/' . $prompt)) {
            new ErrorController(self::ERROR_DELETE_PROMPT_DIRECTORY);
            $this->redirect();
        }

        $this->deleteDirectory(ROOT_DIR . 'prompt/' . $prompt);

        new SuccessController(self::SUCCESS_DELETE_PROMPT_DIRECTORY);

        $this->redirect('/prompt-merger');
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
     * Redirect
     *
     * @param string|null $uri URI
     * @return void
     */
    public function redirect(string|null $uri = null): void
    {
        if ($uri !== null) {
            $location = $_SERVER['HTTP_REFERER'];
            $split = explode('/prompt-merger/', $location);
            $uri = $split[0] . $uri;
            header('Location: ' . $uri);
            exit();
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    /**
     * Get prompts
     *
     * @return array
     */
    public function getPrompts(): array
    {
        return array_keys(self::$promptData);
    }

    /**
     * Prompt exists
     *
     * @param string $prompt Prompt
     * @return bool
     */
    public function promptExists(string $prompt): bool
    {
        if (!isset(self::$promptData[$prompt])) {
            return false;
        }

        return true;
    }

    /**
     * Get prompt files
     *
     * @param string $prompt Prompt
     * @return array|null
     */
    public function getPromptFiles(string $prompt): array|null
    {
       if (!isset(self::$promptData[$prompt])) {
            return null;
       }

       $files = [];
       foreach (self::$promptData[$prompt] as $file) {
           $files[] = [
               'name' => str_replace('.txt', '', $file),
               'content' => file_get_contents(ROOT_DIR . 'prompt/' . $prompt . '/' . $file)
           ];
       }

       return $files;
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