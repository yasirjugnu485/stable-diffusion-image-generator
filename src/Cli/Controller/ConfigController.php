<?php

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Exception\PromptImageGeneratorException;
use Cli\Interface\ConfigInterface;
use DateTime;

class ConfigController implements ConfigInterface
{
    public static bool $initialized = false;

    private static array $configData = [];

    private string $host = 'http://127.0.0.1:7860';

    private bool $startWebApplication = true;

    private bool $saveImages = true;

    private bool $loop = false;

    private string|array|false|null $checkpoint = null;

    private string|array|false|null $sampler = null;

    private string $mode = 'txt2img';

    private string|null $prompt = null;

    private int|null $numberOfImages = 1;

    private int $width = 512;

    private int $height = 512;

    private int $steps = 20;

    private string|array|false|null $refinerCheckpoint = false;

    private float $refinerSwitchAt = 0.8;

    private bool $restoreFaces = true;

    private bool $tiling = false;

    private string|null $initImages = null;

    private bool $enableHr = false;

    private string|null $hrUpscaler = null;

    private int|null $hrResizeX = null;

    private int|null $hrResizeY = null;

    private float|null $hrScale = null;

    private string|null $hrSamplerName = null;

    private bool $dryRun = false;

    private string|null $dateTime = null;

    public function __construct()
    {
        if (count(self::$configData) > 0) {
            return;
        }

        new EchoController();

        $this->initConfigData();
        $this->getOptions();
        $this->initCheckpoints();
        $this->initSamplers();
        $this->initRefiner();
        $this->initInitImages();

        self::$initialized = true;
    }

    private function initConfigData(): void
    {
        new EchoController(self::ECHO_INIT_CONFIG);

        $date = new DateTime('NOW');
        $this->dateTime = $date->format('Y-m-d H:i:s');

        if (!file_exists(ROOT_DIR . 'config.inc.php')) {
            throw new PromptImageGeneratorException(self::ERROR_CONFIG_NOT_FOUND);
        }
        include_once ROOT_DIR . 'config.inc.php';

        if (file_exists(ROOT_DIR . 'config.local.php')) {
            include_once ROOT_DIR . 'config.local.php';
        }

        if (!isset($this->host)) {
            throw new PromptImageGeneratorException(self::ERROR_NO_HOST_CONFIGURED);
        } elseif (!$this->width) {
            throw new PromptImageGeneratorException(self::ERROR_NO_WIDTH_CONFIGURED);
        } elseif (!$this->height) {
            throw new PromptImageGeneratorException(self::ERROR_NO_HEIGHT_CONFIGURED);
        } elseif (!$this->steps) {
            throw new PromptImageGeneratorException(self::ERROR_NO_STEPS_CONFIGURED);
        }

        if (!$this->numberOfImages) {
            $this->numberOfImages = 1000000;
        }
        if ($this->loop) {
            $this->saveImages = true;
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

    private function initCheckpoints(): void
    {
        new CheckpointController();
    }

    private function initSamplers(): void
    {
        new SamplerController();
    }

    private function initRefiner(): void
    {
        new RefinerController();
    }

    private function initInitImages(): void
    {
        new InitImagesController();
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