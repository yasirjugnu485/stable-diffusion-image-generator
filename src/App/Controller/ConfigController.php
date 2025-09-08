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

class ConfigController
{
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
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        if (count(self::$configData) > 0) {
            return;
        }

        $this->initConfigData();
    }

    /**
     * Initialize config data
     *
     * @return void
     */
    private function initConfigData(): void
    {
        if (!file_exists(ROOT_DIR . '/config.app.php')) {
            if (file_exists(ROOT_DIR . '/config.local.php')) {
                copy(ROOT_DIR . '/config.local.php', ROOT_DIR . '/config.app.php');
            } else {
                copy(ROOT_DIR . '/config.app.php', ROOT_DIR . '/config.inc.php');
            }
        }

        include ROOT_DIR . '/config.app.php';
        $this->generateConfigData();
    }

    /**
     * Load config.local.php
     *
     * @return bool
     */
    public function loadConfigLocal(): bool
    {
        if (file_exists(ROOT_DIR . '/config.local.php')) {
            copy(ROOT_DIR . '/config.local.php', ROOT_DIR . '/config.app.php');
            include ROOT_DIR . '/config.app.php';
            $this->generateConfigData();
            return true;
        }

        return false;
    }

    /**
     * Load config.inc.php
     *
     * @return bool
     */
    public function loadConfigInc(): bool
    {
        if (file_exists(ROOT_DIR . '/config.inc.php')) {
            copy(ROOT_DIR . '/config.inc.php', ROOT_DIR . '/config.app.php');
            include ROOT_DIR . '/config.app.php';
            $this->generateConfigData();
            return true;
        }

        return false;
    }

    /**
     * Generate config data
     *
     * @return void
     */
    private function generateConfigData(): void
    {
        $classVars = get_object_vars($this);
        foreach ($classVars as $key => $value) {
            self::$configData[$key] = $value;
        }
    }

    /**
     * Get config data
     *
     * @return array|false
     */
    public function getConfig(): array|false
    {
        if (!count(self::$configData)) {
            return false;
        }
        return self::$configData;
    }
}