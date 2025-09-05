<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL private LICENSE
 */

declare(strict_types=1);

namespace App\Model;

class ConfigModel
{
    /**
     * Host with port
     *
     * @var string
     */
    private string $host = 'http://127.0.0.1:7860';

    /**
     * Start Web-Application
     *
     * @var bool
     */
    private bool $startWebApplication = true;

    /**
     * Number of images to create
     *
     * @var int|null
     */
    private int|null $numberOfImages = 10;

    /**
     * Loop txt2txt -> img2img or loop img2img -> img2img
     *
     * @var bool
     */
    private bool $loop = false;

    /**
     * Dry run
     *
     * @var bool
     */
    private bool $dryRun = false;

    /**
     * Mode
     *
     * @var string
     */
    private string $mode = 'txt2img';

    /**
     * Checkpoint
     *
     * @var string|array|false|null
     */
    private string|array|false|null $checkpoint = null;

    /**
     * Sampler
     *
     * @var string|array|false|null
     */
    private string|array|false|null $sampler = false;

    /**
     * String of prompt generator directory to merge prompt or set null to random select prompt directory
     *
     * @var string|null
     */
    private string|null $prompt = 'test';

    /**
     * String of img2img init image directory or set null to random select init image directory
     *
     * @var string|null
     */
    private string|null $initImages = 'test';

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
     * Refiner checkpoint
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
    private bool $restoreFaces = false;

    /**
     * Tiling
     *
     * @var bool
     */
    private bool $tiling = false;

    /**
     * Enable hires fix
     *
     * @var bool
     */
    private bool $enableHr = false;

    /**
     * Hr upscaler
     *
     * @var string|null
     */
    private string|null $hrUpscaler = null;

    /**
     * Hr resize width
     *
     * @var int|null
     */
    private int|null $hrResizeX = null;

    /**
     * Hr resize height
     *
     * @var int|null
     */
    private int|null $hrResizeY = null;

    /**
     * Hr scale
     *
     * @var float|null
     */
    private float|null $hrScale = 2;

    /**
     * Hr sampler
     *
     * @var string|null
     */
    private string|null $hrSamplerName = null;

    /**
     * Create
     *
     * @return void
     */
    public function create(): void
    {
        $this->collectPostParameters();
        $this->buildConfigApp();
    }

    /**
     * Collect POST parameters
     *
     * @return void
     */
    private function collectPostParameters(): void
    {
        if (isset($_POST['numberOfImages'])) {
            $numberOfImages = (int)$_POST['numberOfImages'];
            if ($numberOfImages > 1000000) {
                $numberOfImages = 1000000;
            }
            $this->numberOfImages = $numberOfImages ?: null;
        }

        if (isset($_POST['loop'])) {
            if ($_POST['loop'] === '1') {
                $this->loop = true;
            }
        }

        if (isset($_POST['mode'])) {
            if ($_POST['mode'] === 'img2img') {
                $this->mode = 'img2img';
            }
        }

        if (isset($_POST['checkpoint'])) {
            if (is_string($_POST['checkpoint']) || is_array($_POST['checkpoint'])) {
                $this->checkpoint = $_POST['checkpoint'];
            } else {
                $this->checkpoint = false;
            }
        } else {
            $this->checkpoint = false;
        }

        if (isset($_POST['sampler'])) {
            if (is_string($_POST['sampler']) || is_array($_POST['sampler'])) {
                $this->sampler = $_POST['sampler'];
            }
        }

        if (isset($_POST['prompt'])) {
            if ($_POST['prompt']) {
                $this->prompt = $_POST['prompt'];
            } else {
                $this->prompt = null;
            }
        } else {
            $this->prompt = null;
        }

        if ($this->mode === 'img2img') {
            if (isset($_POST['initImage'])) {
                if ($_POST['initImage']) {
                    $this->initImages = $_POST['initImage'];
                } else {
                    $this->initImages = null;
                }
            } else {
                $this->initImages = null;
            }
        } else {
            $this->initImages = null;
        }

        if (isset($_POST['width'])) {
            $width = (int)$_POST['width'];
            if ($width <= 8192 && $width >= 10) {
                $this->width = $width;
            }
        }

        if (isset($_POST['height'])) {
            $height = (int)$_POST['height'];
            if ($height <= 8192 && $height >= 10) {
                $this->height = $height;
            }
        }

        if (isset($_POST['steps'])) {
            $steps = (int)$_POST['steps'];
            if ($steps <= 100 && $steps > 0) {
                $this->steps = $steps;
            }
        }

        if (isset($_POST['restoreFaces'])) {
            if ($_POST['restoreFaces'] === '1') {
                $this->restoreFaces = true;
            }
        }

        if (isset($_POST['tiling'])) {
            if ($_POST['tiling'] === '1') {
                $this->tiling = true;
            }
        }

        if (isset($_POST['refinerCheckpoint'])) {
            if (is_string($_POST['refinerCheckpoint']) || is_array($_POST['refinerCheckpoint'])) {
                $this->refinerCheckpoint = $_POST['refinerCheckpoint'];
            }
        }

        if ($this->refinerSwitchAt) {
            if (isset($_POST['refinerSwitchAt'])) {
                $refinerSwitchAt = (int)$_POST['refinerSwitchAt'];
                if ($refinerSwitchAt > 1 && $refinerSwitchAt <= 100) {
                    $this->refinerSwitchAt = $refinerSwitchAt / 100;
                }
            }
        }

        if ($this->mode === 'txt2img') {
            if (isset($_POST['enableHr'])) {
                if ($_POST['enableHr'] === '1') {
                    $this->enableHr = true;
                }
            }
        }

        if ($this->enableHr) {
            if (isset($_POST['hrUpscaler'])) {
                if ($_POST['hrUpscaler'] && $_POST['hrUpscaler'] !== 'None') {
                    $this->hrUpscaler = $_POST['hrUpscaler'];
                }
            }

            if (isset($_POST['hrResizeX'])) {
                $hrResizeX = (int)$_POST['hrResizeX'];
                if ($hrResizeX <= 8192 && $hrResizeX > 512) {
                    $this->hrResizeX = $hrResizeX;
                }
            }

            if (isset($_POST['hrResizeY'])) {
                $hrResizeY = (int)$_POST['hrResizeY'];
                if ($hrResizeY <= 8192 && $hrResizeY > 512) {
                    $this->hrResizeY = $hrResizeY;
                }
            }

            if (isset($_POST['hrScale'])) {
                $hrScale = (int)$_POST['hrScale'];
                if ($hrScale <= 4 && $hrScale > 0) {
                    $this->hrScale = $hrScale;
                    $this->hrResizeX = 0;
                    $this->hrResizeY = 0;
                }
            }

            if (isset($_POST['hrSamplerName'])) {
                if (is_string($_POST['hrSamplerName']) || is_array($_POST['hrSamplerName'])) {
                    $this->hrSamplerName = $_POST['hrSamplerName'];
                }
            }
        }
    }

    /**
     * Build config.app.php
     *
     * @return void
     */
    private function buildConfigApp(): void
    {
        $config = file_get_contents(ROOT_DIR . 'templates/config.php');
        $classVars = get_object_vars($this);
        foreach ($classVars as $key => $value) {
            if (!str_contains($config, "['" . $key . "']")) {
                continue;
            }
            if (is_string($value)) {
                $this->buildStringVariable($key, $value, $config);
            } elseif (is_int($value)) {
                $this->buildIntVariable($key, $value, $config);
            } elseif (is_bool($value)) {
                $this->buildBoolVariable($key, $value, $config);
            } elseif (is_array($value)) {
                $this->buildArrayVariable($key, $value, $config);
            } elseif (is_null($value)) {
                $this->buildNullVariable($key, $config);
            } elseif (is_float($value) || is_double($value)) {
                $this->buildFloatVariable($key, $value, $config);
            }

        }

        file_put_contents(ROOT_DIR . 'config.app.php', $config);
    }

    /**
     * Build string variable
     *
     * @param string $key Key
     * @param string $value Value
     * @param string $config Config file
     * @return void
     */
    private function buildStringVariable(string $key, string $value, string &$config): void
    {
        $config = str_replace("['" . $key . "']", "'" . $value . "'", $config);
    }

    /**
     * Build int variable
     *
     * @param string $key Key
     * @param int $value Value
     * @param string $config Config file
     * @return void
     */
    private function buildIntVariable(string $key, int $value, string &$config): void
    {
        $config = str_replace("['" . $key . "']", (string)$value, $config);
    }

    /**
     * Build float variable
     *
     * @param string $key Key
     * @param float $value Value
     * @param string $config Config file
     * @return void
     */
    private function buildFloatVariable(string $key, float $value, string &$config): void
    {
        $config = str_replace("['" . $key . "']", (string)$value, $config);
    }

    /**
     * Build bool variable
     *
     * @param string $key Key
     * @param bool $value Value
     * @param string $config Config file
     * @return void
     */
    private function buildBoolVariable(string $key, bool $value, string &$config): void
    {
        if (!$value) {
            $config = str_replace("['" . $key . "']", 'false', $config);
        } else {
            $config = str_replace("['" . $key . "']", 'true', $config);
        }
    }

    /**
     * Build array variable
     *
     * @param string $key Key
     * @param array $value Value
     * @param string $config Config file
     * @return void
     */
    private function buildArrayVariable(string $key, array $value, string &$config): void
    {
        $string = '[';
        foreach ($value as $item) {
            $string .= "'" . $item . "',";
        }
        $string .= ']';
        $config = str_replace("['" . $key . "']", $string, $config);
    }

    /**
     * Build null variable
     *
     * @param string $key Key
     * @param string $config Config file
     * @return void
     */
    private function buildNullVariable(string $key, string &$config): void
    {
        $config = str_replace("['" . $key . "']", 'null', $config);
    }
}