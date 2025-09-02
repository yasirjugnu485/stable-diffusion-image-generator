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

class PromptCollectorController
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
        $this->collectPrompts();
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
     * Get prompt
     *
     * @return array
     */
    public function getPrompts(): array
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