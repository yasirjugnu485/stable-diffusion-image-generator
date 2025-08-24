<?php

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Exception\PromptImageGeneratorException;
use Cli\Interface\PromptInterface;

class PromptController implements PromptInterface
{
    private static array $promptData = [];

    private static string|null $lastPrompt = null;

    private static string|null $currentPrompt;

    public function __construct()
    {
        if (count(self::$promptData) > 0) {
            return;
        }

        $this->loadPromptData();
    }

    private function loadPromptData(): void
    {
        new EchoController(self::ECHO_START);

        $configController = new ConfigController();
        $config = $configController->getConfig();

        if (!is_dir(ROOT_DIR . 'prompt')) {
            throw new PromptImageGeneratorException(self::ERROR_NO_PROMPT_DIRECTORY_FOUND);
        }

        $promptDirectories = array_filter(glob(ROOT_DIR . 'prompt/*'), 'is_dir');
        if (empty($promptDirectories)) {
            throw new PromptImageGeneratorException(self::ERROR_NO_PROMPT_SUBDIRECTORIES_FOUND);
        }

        $promptsData = [];
        foreach ($promptDirectories as $promptDirectory) {
            $name = str_replace(ROOT_DIR . 'prompt/', '', $promptDirectory);
            $files = array_filter(glob($promptDirectory . '/*'), 'is_file');
            if (empty($files)) {
                continue;
            }
            foreach ($files as $file) {
                $promptData = [];
                $data = file_get_contents($file);
                $prompts = explode(PHP_EOL, $data);
                foreach ($prompts as $prompt) {
                    $prompt = trim($prompt);
                    if ($prompt) {
                        $promptData[] = $prompt;
                    }
                }
                if (empty($promptData)) {
                    continue;
                }
                if (!isset($promptsData[$name])) {
                    $promptsData[$name] = [];
                }
                $promptsData[$name][] = $promptData;
            }
        }
        if (empty($promptsData)) {
            throw new PromptImageGeneratorException(self::ERROR_NO_PROMPT_DATA_FOUND);
        }

        self::$promptData = $promptsData;
        if ($config['prompt'] !== null) {
            if (!array_key_exists($config['prompt'], self::$promptData)) {
                throw new PromptImageGeneratorException(self::ERROR_CONFIGURED_PROMPT_NOT_FOUND);
            }
            self::$currentPrompt = $config['prompt'];
        } else {
            self::$currentPrompt = array_key_first(self::$promptData);
        }

        new EchoController(self::ECHO_END);
        new EchoController();
    }

    public function getNextPrompt(): string
    {
        self::$lastPrompt = self::$currentPrompt;

        $prompt = [];
        foreach (self::$promptData[self::$currentPrompt] as $promptData) {
            $rand = array_rand($promptData);
            $prompt[] = $promptData[$rand];
        }

        $configController = new ConfigController();
        $config = $configController->getConfig();
        if ($config['prompt'] === null) {
            $currentPrompt = self::$currentPrompt;
            self::$currentPrompt = null;
            $next = false;
            foreach (self::$promptData as $promptKey => $promptData) {
                if ($next) {
                    self::$currentPrompt = $promptKey;
                    break;
                } elseif ($currentPrompt === $promptKey) {
                    $next = true;
                }
            }
            if (self::$currentPrompt === null) {
                self::$currentPrompt = array_key_first(self::$promptData);
            }
        }

        return implode(' ', $prompt);
    }

    public function getLastPrompt(): string
    {
        return self::$lastPrompt;
    }
}