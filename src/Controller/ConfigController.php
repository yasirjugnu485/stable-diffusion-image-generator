<?php

declare(strict_types=1);

namespace Controller;

use DateTime;
use Exception\PromptImageGeneratorException;
use Interface\ConfigInterface;

class ConfigController implements ConfigInterface
{
    public static bool $initialized = false;

    private static array $configData = [];

    private string $host = 'http://127.0.0.1:7860';

    private bool $saveImages = true;

    private string|array|false|null $model = null;

    private string $mode = 'txt2img';

    private string|null $prompt = null;

    private int|null $numberOfImages = 1;

    private int $width = 512;

    private int $height = 512;

    private int $steps = 25;

    private string|null $initImages = null;

    private string|null $dateTime = null;

    private bool $dryRun = false;

    public function __construct()
    {
        if (count(self::$configData) > 0) {
            return;
        }

        new EchoController();

        $this->initConfigData();
        $this->getOptions();
        $this->getModels();

        self::$initialized = true;
    }

    private function initConfigData(): void
    {
        new EchoController(self::ECHO_INIT_CONFIG);

        $date = new DateTime('NOW');
        $this->dateTime = $date->format('Y-m-d H:i:s');

        if (!file_exists('config.inc.php')) {
            throw new PromptImageGeneratorException(self::ERROR_CONFIG_NOT_FOUND);
        }
        include __DIR__ . '/../../config.inc.php';

        if (file_exists(__DIR__ . '/../../config.local.php')) {
            include __DIR__ . '/../../config.local.php';
        }

        if (!isset($this->host)) {
            throw new PromptImageGeneratorException(self::ERROR_NO_HOST_CONFIGURED);
        } elseif (!$this->numberOfImages) {
            throw new PromptImageGeneratorException(self::ERROR_NO_NUMBER_OF_IMAGES_CONFIGURED);
        } elseif (!$this->width) {
            throw new PromptImageGeneratorException(self::ERROR_NO_WIDTH_CONFIGURED);
        } elseif (!$this->height) {
            throw new PromptImageGeneratorException(self::ERROR_NO_HEIGHT_CONFIGURED);
        } elseif (!$this->steps) {
            throw new PromptImageGeneratorException(self::ERROR_NO_STEPS_CONFIGURED);
        }

        if ($this->numberOfImages === null) {
            $this->numberOfImages = 0;
        }

        $classVars = get_object_vars($this);
        foreach ($classVars as $key => $value) {
            self::$configData[$key] = $value;
        }

        new EchoController(self::SUCCESS_INIT_CONFIG);
        new EchoController();

        if ($this->dryRun) {
            new EchoController(self::ECHO_DRY_RUN_IS_ACTIVATED);
            new EchoController();
        }
    }

    public function getModels(): void
    {
        new ModelController();
    }

    public function getOptions(): void
    {
        new OptionController();
    }

    public function getConfig(): array
    {
        if (!count(self::$configData)) {
            throw new PromptImageGeneratorException(self::ERROR_CONFIG_NOT_LOADED);
        }
        return self::$configData;
    }
}