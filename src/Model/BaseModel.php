<?php

declare(strict_types=1);

namespace Model;

use Controller\CheckpointController;
use Controller\RefinerController;

class BaseModel
{
    protected string|null $prompt = null;

    protected string|null $negativePrompt = null;

    protected int $width;

    protected int $height;

    protected int $steps;

    protected array $overrideSettings = [];

    protected string|null $refinerCheckpoint = null;

    protected float|null $refinerSwitchAt = null;

    protected bool $restoreFaces = true;

    public function __construct()
    {}

    public function toJson(): string
    {
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
        if (count($this->overrideSettings)) {
            $toJson['override_settings'] = $this->overrideSettings;
            $toJson['override_settings_restore_afterwards'] = true;
        }

        return json_encode($toJson);
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