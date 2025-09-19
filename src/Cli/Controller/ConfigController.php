<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Exception\PromptImageGeneratorException;
use Cli\Interface\ConfigInterface;
use DateTime;

class ConfigController implements ConfigInterface
{
    /**
     * Config is initialized
     *
     * @var bool
     */
    public static bool $initialized = false;

    /**
     * Config data
     *
     * @var array
     */
    private static array $configData = [];

    /**
     * Host and port
     *
     * @var string
     */
    private string $host = 'http://127.0.0.1:7860';

    /**
     * Start web application
     *
     * @var bool
     */
    private bool $startWebApplication = true;

    /**
     * Save images
     *
     * @var bool
     */
    private bool $saveImages = true;

    /**
     * Use loop function
     *
     * @var bool
     */
    private bool $loop = false;

    /**
     * Used checkpoints
     *
     * @var string|array|false|null
     */
    private string|array|false|null $checkpoint = null;

    /**
     * Used samplers
     *
     * @var string|array|false|null
     */
    private string|array|false|null $sampler = null;

    /**
     * Used mode
     *
     * @var string
     */
    private string $mode = 'txt2img';

    /**
     * Used prompt merger
     *
     * @var string|null
     */
    private string|null $prompt = null;

    /**
     * Used negative prompt merger
     *
     * @var string|null
     */
    private string|null $negativePrompt = null;

    /**
     * Number of images to generate
     *
     * @var int|null
     */
    private int|null $numberOfImages = 1;

    /**
     * Image width
     *
     * @var int
     */
    private int $width = 512;

    /**
     * Image height
     *
     * @var int
     */
    private int $height = 512;

    /**
     * Number ob sampling steps
     *
     * @var int
     */
    private int $steps = 20;

    /**
     * Used refiner checkpoints
     *
     * @var string|array|false|null
     */
    private string|array|false|null $refinerCheckpoint = false;

    /**
     * Refiner switch at
     *
     * @var float
     */
    private float $refinerSwitchAt = 0.8;

    /**
     * Used loras
     *
     * @var string|array|null
     */
    private string|array|null $lora = null;

    /**
     * Restore faces
     *
     * @var bool
     */
    private bool $restoreFaces = true;

    /**
     * Tiling
     *
     * @var bool
     */
    private bool $tiling = false;

    /**
     * Used init images directory
     *
     * @var string|null
     */
    private string|null $initImages = null;

    /**
     * Enable high resolution upscaling
     *
     * @var bool
     */
    private bool $enableHr = false;

    /**
     * Used upscaler for high resolution upscaling
     *
     * @var string|null
     */
    private string|null $hrUpscaler = null;

    /**
     * Resize width for high resolution upscaling
     *
     * @var int|null
     */
    private int|null $hrResizeX = null;

    /**
     * Resize height for high resolution upscaling
     *
     * @var int|null
     */
    private int|null $hrResizeY = null;

    /**
     * Scale for high resolution upscaling
     *
     * @var float|null
     */
    private float|null $hrScale = null;

    /**
     * Used sampler for high resolution upscaling
     *
     * @var string|null
     */
    private string|null $hrSamplerName = null;

    /**
     * Dry run
     *
     * @var bool
     */
    private bool $dryRun = false;

    /**
     * Date time CLI application started
     *
     * @var string|null
     */
    private string|null $dateTime = null;

    /**
     * Constructor
     *
     * @return void
     * @throws PromptImageGeneratorException
     */
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
        $this->initLora();
        $this->initInitImages();

        self::$initialized = true;
    }

    /**
     * Initialize config data
     *
     * @return void
     * @throws PromptImageGeneratorException
     */
    private function initConfigData(): void
    {
        new EchoController(self::ECHO_INIT_CONFIG);

        $date = new DateTime('NOW');
        $this->dateTime = $date->format('Ymd-His');

        if (!file_exists(ROOT_DIR . 'config.inc.php')) {
            throw new PromptImageGeneratorException(self::ERROR_CONFIG_NOT_FOUND);
        }
        include_once ROOT_DIR . 'config.inc.php';

        $bootstrapController = new BootstrapController();
        $arguments = $bootstrapController->getArguments();

        if (isset($arguments['--config'])) {
            include_once $arguments['--config'][0];
        } else {
            if (file_exists(ROOT_DIR . 'config.local.php')) {
                include_once ROOT_DIR . 'config.local.php';
            }
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

    /**
     * Initialize checkpoints
     *
     * @return void
     */
    private function initCheckpoints(): void
    {
        new CheckpointController();
    }

    /**
     * Initialize samplers
     *
     * @return void
     */
    private function initSamplers(): void
    {
        new SamplerController();
    }

    /**
     * Initialize refiner
     *
     * @return void
     */
    private function initRefiner(): void
    {
        new RefinerController();
    }

    /**
     * Initialize lora
     *
     * @return void
     */
    private function initLora(): void
    {
        new LoraController();
    }

    /**
     * Initialize initialize images
     *
     * @return void
     */
    private function initInitImages(): void
    {
        new InitImagesController();
    }

    /**
     * Get options
     *
     * @return void
     */
    public function getOptions(): void
    {
        new OptionController();
    }

    /**
     * Get config data
     *
     * @return array
     * @throws PromptImageGeneratorException
     */
    public function getConfig(): array
    {
        if (!count(self::$configData)) {
            throw new PromptImageGeneratorException(self::ERROR_CONFIG_NOT_LOADED);
        }
        return self::$configData;
    }
}