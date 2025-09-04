<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace Cli\Model;

use Cli\Controller\CheckpointController;
use Cli\Controller\ConfigController;
use Cli\Controller\RefinerController;
use Cli\Controller\SamplerController;
use Cli\Exception\PromptImageGeneratorException;

class BaseModel
{
    /**
     * Prompt
     *
     * @var string|null
     */
    protected string|null $prompt = null;

    /**
     * Negative prompt
     *
     * @var string|null
     */
    protected string|null $negativePrompt = null;

    /**
     * Image width
     *
     * @var int
     */
    protected int $width;

    /**
     * Image height
     *
     * @var int
     */
    protected int $height;

    /**
     * Number of sampling steps
     *
     * @var int
     */
    protected int $steps;

    /**
     * Sampler name
     *
     * @var string|null
     */
    protected string|null $samplerName = null;

    /**
     * Override settings
     *
     * @var array
     */
    protected array $overrideSettings = [];

    /**
     * Refiner checkpoint
     *
     * @var string|null
     */
    protected string|null $refinerCheckpoint = null;

    /**
     * Refiner switch at
     *
     * @var float|null
     */
    protected float|null $refinerSwitchAt = null;

    /**
     * Restore faces
     *
     * @var bool
     */
    protected bool $restoreFaces = true;

    /**
     * Tiling
     *
     * @var bool
     */
    protected bool $tiling = false;

    /**
     * Enable high resolution upscaling
     *
     * @var bool
     */
    protected bool $enableHr = false;

    /**
     * Upscaler for high resolution upscaling
     *
     * @var string|null
     */
    protected string|null $hrUpscaler = null;

    /**
     * Resize width for high resolution upscaling
     *
     * @var int|null
     */
    protected int|null $hrResizeX = null;

    /**
     * Resize height for high resolution upscaling
     *
     * @var int|null
     */
    protected int|null $hrResizeY = null;

    /**
     * Scale for high resolution upscaling
     *
     * @var float|null
     */
    protected float|null $hrScale = null;

    /**
     * Sampler for high resolution upscaling
     *
     * @var string|null
     */
    protected string|null $hrSamplerName = null;

    /**
     * Constructor
     *
     * @throws PromptImageGeneratorException
     */
    public function __construct()
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $this->height = $config['height'];
        $this->width = $config['width'];
        $this->steps = $config['steps'];
        $this->refinerSwitchAt = $config['refinerSwitchAt'];
        $this->restoreFaces = $config['restoreFaces'];
        $this->tiling = $config['tiling'];
        if ($config['enableHr']) {
            if ($config['hrScale'] !== null) {
                $this->enableHr = true;
                $this->hrUpscaler = $config['hrUpscaler'] ?? 'Latent';
                $this->hrScale = $config['hrScale'];
                $this->hrSamplerName = $config['hrSamplerName'] ?? 'Euler';
            } elseif ($config['hrResizeX'] !== null && $config['hrResizeY'] !== null) {
                $this->enableHr = true;
                $this->hrUpscaler = $config['hrUpscaler'] ?? 'Latent';
                $this->hrResizeX = $config['hrResizeX'];
                $this->hrResizeY = $config['hrResizeY'];
                $this->hrSamplerName = $config['hrSamplerName'] ?? 'Euler';
            }
        }
    }

    /**
     * Convert to JSON payload
     *
     * @return string
     */
    public function toJson(): string
    {
        $this->setSamplerName();
        $this->setOverrideSettings();
        $this->setRefiner();

        $toJson = [
            'width' => $this->width,
            'height' => $this->height,
            'steps' => $this->steps,
            'restore_faces' => $this->restoreFaces,
        ];
        if ($this->prompt) {
            $toJson['prompt'] = $this->prompt;
        }
        if ($this->negativePrompt) {
            $toJson['negative_prompt'] = $this->negativePrompt;
        }
        if ($this->refinerCheckpoint) {
            $toJson['refiner_checkpoint'] = $this->refinerCheckpoint;
            $toJson['refiner_switch_at'] = $this->refinerSwitchAt;
        }
        if ($this->samplerName) {
            $toJson['sampler_name'] = $this->samplerName;
        }
        if (count($this->overrideSettings)) {
            $toJson['override_settings'] = $this->overrideSettings;
            $toJson['override_settings_restore_afterwards'] = true;
        }

        return json_encode($toJson);
    }

    /**
     * Set sampler name
     *
     * @return void
     */
    private function setSamplerName(): void
    {
        $samplerController = new SamplerController();
        $samplerController->setNextSampler();
        $currentSampler = $samplerController->getCurrentSampler();
        if ($currentSampler) {
            $this->samplerName = $currentSampler;
        } else {
            $this->samplerName = null;
        }
    }

    /**
     * Set override settings
     *
     * @return void
     */
    private function setOverrideSettings(): void
    {
        $checkpointController = new CheckpointController();
        $checkpointController->setNextCheckpoint();
        $currentCheckpoint = $checkpointController->getCurrentCheckpoint();
        if ($currentCheckpoint) {
            $this->overrideSettings['sd_model_checkpoint'] = $currentCheckpoint;
        } else {
            if (isset($this->overrideSettings['sd_model_checkpoint'])) {
                unset($this->overrideSettings['sd_model_checkpoint']);
            }
        }
    }

    /**
     * Set refiner
     *
     * @return void
     */
    private function setRefiner(): void
    {
        $refinerController = new RefinerController();
        $refinerController->setNextCheckpoint();
        $this->refinerCheckpoint = $refinerController->getCurrentCheckpoint();
    }
}