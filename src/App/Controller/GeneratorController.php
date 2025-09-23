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

use App\Interface\Interface\GeneratorInterface;
use App\Model\ConfigModel;
use App\Service\StableDiffusionService;

class GeneratorController implements GeneratorInterface
{
    /**
     * Checkpoints
     *
     * @var array|null
     */
    private static array|null $checkpoints = null;

    /**
     * Samplers
     *
     * @var array|null
     */
    private static array|null $samplers = null;

    /**
     * Prompt directories
     *
     * @var array|null
     */
    private static array|null $prompts = null;

    /**
     * Loras
     *
     * @var array|null
     */
    private static array|null $loras = null;

    /**
     * Init images directories
     *
     * @var array|null
     */
    private static array|null $initImages = null;

    /**
     * Upscalers
     *
     * @var array|null = null
     */
    private static array|null $upscalers = null;

    /**
     * Error message
     *
     * @var string|null
     */
    private static string|null $error = null;

    /**
     * Config is initialized
     *
     * @var bool
     */
    public static bool $initialized = false;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        if (self::$initialized) {
            return;
        }
        self::$initialized = true;

        $configController = new ConfigController();
        $config = $configController->getConfig();
        if (!$config) {
            self::$error = self::ERROR_ON_LOAD_CONFIG;
            return;
        } elseif (!$this->collectCheckpoints($config['host'])) {
            self::$error = self::ERROR_ON_LOAD_CHECKPOINTS;
            return;
        } elseif (!$this->collectSamplers($config['host'])) {
            self::$error = self::ERROR_ON_LOAD_SAMPLERS;
            return;
        } elseif (!$this->collectPrompts()) {
            self::$error = self::ERROR_ON_LOAD_PROMPTS;
            return;
        }

        $this->collectInitImages();
        $this->collectUpscalers($config['host']);
        $this->collectLoras($config['host']);

        $this->handleAction();
    }

    /**
     * Collect checkpoints
     *
     * @param string $host Host
     * @return bool
     */
    private function collectCheckpoints(string $host): bool
    {
        self::$checkpoints = [];

        $stableDiffusionService = new StableDiffusionService();
        $stableDiffusionService->getSdModels($host);
        if (!file_exists(ROOT_DIR . 'checkpoints.json')) {
            return false;
        }

        $checkpoints = json_decode(file_get_contents(ROOT_DIR . 'checkpoints.json'), true);
        foreach ($checkpoints as $checkpoint) {
            self::$checkpoints[] = $checkpoint['model_name'];
        }

        return true;
    }

    /**
     * Collect samplers
     *
     * @var string $host Host
     * @return bool
     */
    private function collectSamplers(string $host): bool
    {
        self::$samplers = [];

        $stableDiffusionService = new StableDiffusionService();
        $stableDiffusionService->getSamplers($host);
        if (!file_exists(ROOT_DIR . 'samplers.json')) {
            return false;
        }

        $samplers = json_decode(file_get_contents(ROOT_DIR . 'samplers.json'), true);
        foreach ($samplers as $sampler) {
            self::$samplers[] = $sampler['name'];
        }

        return true;
    }

    /**
     * Collect loras
     *
     * @return void
     *@var string $host Host
     */
    private function collectLoras(string $host): void
    {
        self::$loras = [];

        $stableDiffusionService = new StableDiffusionService();
        $stableDiffusionService->getLoras($host);
        if (!file_exists(ROOT_DIR . 'loras.json')) {
            return;
        }

        $loras = json_decode(file_get_contents(ROOT_DIR . 'loras.json'), true);
        foreach ($loras as $lora) {
            $keywords = [];
            if (isset($lora['metadata']['ss_tag_frequency']) && is_array($lora['metadata']['ss_tag_frequency'])) {
                $keywords = $lora['metadata']['ss_tag_frequency'];
            }
            self::$loras[$lora['name']] = [
                'name' => $lora['name'],
                'alias' => $lora['alias'],
                'keywords' => $keywords,
            ];
        }
    }

    /**
     * Collect prompts directories
     *
     * @return bool
     */
    private function collectPrompts(): bool
    {
        $promptController = new PromptController();
        $prompts = $promptController->getPromptDirectories();
        foreach ($prompts as $prompt) {
            self::$prompts[] = $prompt;
        }

        return count(self::$prompts) > 0;
    }

    /**
     * Collect initialize images directories
     *
     * @return void
     */
    private function collectInitImages(): void
    {
        $initImageController = new InitImageController();
        $initImages = $initImageController->getInitImagesDirectories();
        foreach ($initImages as $initImage) {
            self::$initImages[] = $initImage;
        }
    }

    /**
     * Collect upscalers
     *
     * @param string $host Host
     * @return void
     */
    private function collectUpscalers(string $host): void
    {
        $stableDiffusionService = new StableDiffusionService();
        $upscalers = $stableDiffusionService->getUpscalers($host);
        if (!$upscalers) {
            self::$upscalers = [];
            return;
        }

        foreach ($upscalers as $upscaler) {
            self::$upscalers[] = $upscaler['name'];
        }
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        $configController = new ConfigController();
        $configData = $configController->getConfig();
        if (!$configData) {
            return [
                'error' => self::$error ?: self::ERROR_ON_LOAD_CONFIG,
            ];
        }
        if (!isset($configData['host'])) {
            new ErrorController(self::ERROR_HOST_NOT_CONFIGURED);
            new RedirectController('/settings');
        }
        $stableDiffusionService = new StableDiffusionService();
        $ping = $stableDiffusionService->ping($configData['host']);
        var_export($configData['host']);
        if (!$ping) {
            new ErrorController(sprintf(self::ERROR_HOST_NOT_REACHABLE, $configData['host']));
            new RedirectController('/settings');
        }

        if (isset($configData['refinerSwitchAt'])) {
            if (is_float($configData['refinerSwitchAt'])) {
                $configData['refinerSwitchAt'] = $configData['refinerSwitchAt'] * 100;
            } else {
                $configData['refinerSwitchAt'] = 70;
            }
        }
        if ($configData['refinerSwitchAt'] > 100) {
            $configData['refinerSwitchAt'] = 100;
        }

        $checkpoints = $this->getCheckpoints($configData);
        $samplers = $this->getSamplers($configData);
        $refinerCheckpoints = $this->getRefinerCheckpoints($configData);
        $loras = $this->getLoras($configData);
        $loraKeywords = $this->getLoraKeywords($configData, true);
        $prompts = $this->getPrompts($configData);
        $negativePrompts = $this->getNegativePrompts($configData);
        $initImages = $this->getInitImages($configData);
        if (!count(self::$upscalers)) {
            $configData['upscaler'] = null;
        }

        return [
            'config' => $configData,
            'checkpoints' => $checkpoints,
            'refiner_checkpoints' => $refinerCheckpoints,
            'lora_keywords' => $loraKeywords,
            'prompts' => $prompts,
            'negative_prompts' => $negativePrompts,
            'initImages' => $initImages,
            'upscalers' => self::$upscalers,
            'loras' => $loras,
            'samplers' => $samplers,
            'error' => self::$error,
        ];
    }

    /**
     * Get checkpoints
     *
     * @param array $configData Config data
     * @return array
     */
    private function getCheckpoints(array $configData): array
    {
        $checkpoints = [];
        foreach (self::$checkpoints as $checkpoint) {
            $selected = false;
            if (is_string($configData['checkpoint'])) {
                if ($checkpoint === $configData['checkpoint']) {
                    $selected = true;
                }
            } elseif (is_array($configData['checkpoint'])) {
                if (in_array($checkpoint, $configData['checkpoint'])) {
                    $selected = true;
                }
            }
            $checkpoints[] = [
                'name' => $checkpoint,
                'selected' => $selected,
            ];
        }

        return $checkpoints;
    }

    /**
     * Get samplers
     *
     * @param array $configData Config data
     * @return array
     */
    private function getSamplers(array $configData): array
    {
        $samplers = [];
        foreach (self::$samplers as $sampler) {
            $selected = false;
            if (is_string($configData['sampler'])) {
                if ($sampler === $configData['sampler']) {
                    $selected = true;
                }
            } elseif (is_array($configData['sampler'])) {
                if (in_array($sampler, $configData['sampler'])) {
                    $selected = true;
                }
            }
            $samplers[] = [
                'name' => $sampler,
                'selected' => $selected,
            ];
        }

        return $samplers;
    }

    /**
     * Get refiner checkpoints
     *
     * @param array $configData Config data
     * @return array
     */
    private function getRefinerCheckpoints(array $configData): array
    {
        $refinerCheckpoints = [];
        foreach (self::$checkpoints as $checkpoint) {
            $selected = false;
            if (is_string($configData['refinerCheckpoint'])) {
                if ($checkpoint === $configData['refinerCheckpoint']) {
                    $selected = true;
                }
            } elseif (is_array($configData['refinerCheckpoint'])) {
                if (in_array($checkpoint, $configData['refinerCheckpoint'])) {
                    $selected = true;
                }
            }
            $refinerCheckpoints[] = [
                'name' => $checkpoint,
                'selected' => $selected,
            ];
        }

        return $refinerCheckpoints;
    }

    /**
     * Get loras
     *
     * @param array $configData Config data
     * @return array
     */
    private function getLoras(array $configData): array
    {
        $loras = [];
        foreach (self::$loras as $lora) {
            $selected = false;
            if (is_string($configData['lora'])) {
                if ($lora['alias'] === $configData['lora']) {
                    $selected = true;
                }
            } elseif (is_array($configData['lora'])) {
                if (in_array($lora['alias'], $configData['lora'])) {
                    $selected = true;
                }
            }
            $loras[] = [
                'name' => $lora['alias'],
                'selected' => $selected,
            ];
        }

        return $loras;
    }

    /**
     * Get lora keywords
     *
     * @param array $configData Config data
     * @param bool $asJson Return as JSON string
     * @return array|string
     */
    public function getLoraKeywords(array $configData, bool $asJson = false): array|string
    {
        $configLoraKeyword = is_string($configData['loraKeywords']) ? $configData['loraKeywords'] : '';

        $loraKeywords = [];
        foreach (self::$loras as $lora) {
            $addKeywords = [
                'alias' => $lora['alias'],
                'groups' => [],
            ];
            foreach ($lora['keywords'] as $group => $keywords) {
                $addGroup = [
                    'name' => $group,
                    'keywords' => [],
                ];
                foreach ($keywords as $keyword => $trainingUnits) {
                    $selected = false;
                    if (str_contains($configLoraKeyword, $keyword)) {
                        $selected = true;
                    }
                    $addGroup['keywords'][] = [
                        'name' => $keyword,
                        'selected' => $selected,
                        'training_units' => $trainingUnits
                    ];
                }
                $addKeywords['groups'][] = $addGroup;
            }

            $loraKeywords[] = $addKeywords;
        }

       return $asJson ? json_encode($loraKeywords, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : $loraKeywords;
    }

    /**
     * Get prompts
     *
     * @param array $configData Config data
     * @return array
     */
    private function getPrompts(array $configData): array
    {
        $prompts = [];
        foreach (self::$prompts as $prompt) {
            $selected = false;
            if (is_string($configData['prompt'])) {
                if ($prompt === $configData['prompt']) {
                    $selected = true;
                }
            } elseif (is_array($configData['prompt'])) {
                if (in_array($prompt, $configData['prompt'])) {
                    $selected = true;
                }
            }
            $prompts[] = [
                'name' => $prompt,
                'selected' => $selected,
            ];
        }

        return $prompts;
    }

    /**
     * Get negative prompts
     *
     * @param array $configData Config data
     * @return array
     */
    private function getNegativePrompts(array $configData): array
    {
        $negativePrompts = [];
        foreach (self::$prompts as $prompt) {
            $selected = false;
            if (is_string($configData['negativePrompt'])) {
                if ($prompt === $configData['negativePrompt']) {
                    $selected = true;
                }
            } elseif (is_array($configData['negativePrompt'])) {
                if (in_array($prompt, $configData['negativePrompt'])) {
                    $selected = true;
                }
            }
            $negativePrompts[] = [
                'name' => $prompt,
                'selected' => $selected,
            ];
        }

        return $negativePrompts;
    }

    /**
     * Get initialize images
     *
     * @param array $configData Config data
     * @return array
     */
    private function getInitImages(array $configData): array
    {
        $initImages = [];
        foreach (self::$initImages as $initImage) {
            $selected = false;
            if (is_string($configData['initImages'])) {
                if ($initImage === $configData['initImages']) {
                    $selected = true;
                }
            } elseif (is_array($configData['initImages'])) {
                if (in_array($initImages, $configData['initImages'])) {
                    $selected = true;
                }
            }
            $initImages[] = [
                'name' => $initImage,
                'selected' => $selected,
            ];
        }

        return $initImages;
    }

    /**
     * Handle actions
     *
     * @return void
     */
    private function handleAction(): void
    {
        if (!self::$error) {
            if (isset($_POST['action']) && $_POST['action'] === 'generate') {
                $this->generate();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'save') {
                $this->save();
            }
        }
    }

    /**
     * Generate
     *
     * @return void
     */
    private function generate(): void
    {
        $configModel = new ConfigModel();
        $configModel->create();

        $_SESSION['GeneratorController'] = true;

        $this->redirect();
    }

    /**
     * Save
     *
     * @return void
     */
    private function save(): void
    {
        $configModel = new ConfigModel();
        $configModel->create();

        new SuccessController(self::SUCCESS_SAVE_CONFIG_APP_PHP);

        $this->redirect();
    }

    /**
     * Redirect
     *
     * @return void
     */
    public function redirect(): void
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    /**
     * Get start generating
     *
     * @return bool
     */
    public function getStartGeneration(): bool
    {
        if (!isset($_SESSION['GeneratorController'])) {
            return false;
        }

        unset($_SESSION['GeneratorController']);
        return true;
    }
}