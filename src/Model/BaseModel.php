<?php

declare(strict_types=1);

namespace Model;

use Controller\CheckpointController;
use Controller\ConfigController;
use Controller\RefinerController;
use Controller\SamplerController;

class BaseModel
{
    protected string|null $prompt = null;

    protected string|null $negativePrompt = null;

    protected int $width;

    protected int $height;

    protected int $steps;

    protected string|null $samplerName = null;

    protected array $overrideSettings = [];

    protected string|null $refinerCheckpoint = null;

    protected float|null $refinerSwitchAt = null;

    protected bool $restoreFaces = true;

    protected bool $tiling = false;

    protected bool $enableHr = false;

    protected string|null $hrUpscaler = null;

    protected int|null $hrResizeX = null;

    protected int|null $hrResizeY = null;

    protected float|null $hrScale = null;

    protected string|null $hrSamplerName = null;

    public function __construct()
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $this->height = $config['height'];
        $this->width = $config['width'];
        $this->steps = $config['steps'];
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

    private function setRefiner(): void
    {
        $refinerController = new RefinerController();
        $refinerController->setNextCheckpoint();
        $this->refinerCheckpoint = $refinerController->getCurrentCheckpoint();
    }
}