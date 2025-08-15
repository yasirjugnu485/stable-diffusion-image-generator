<?php

declare(strict_types=1);

namespace Model;

use Controller\ConfigController;

class txt2imgModel
{
    private string|null $prompt = null;

    private string|null $negativePrompt = null;

    private int $width;

    private int $height;

    private int $steps;

    public function __construct()
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();
        $this->height = $config['height'];
        $this->width = $config['width'];
        $this->steps = $config['steps'];
    }

    public function setPrompt(string $prompt): static
    {
        $this->prompt = $prompt;
        return $this;
    }

    public function setNegativePrompt(string $negativePrompt): static
    {
        $this->negativePrompt = $negativePrompt;
        return $this;
    }

    public function toJson(): string
    {
        $toJson = [
           'width' => $this->width,
           'height' => $this->height,
           'steps' => $this->steps,
        ];
        if ($this->prompt) {
            $toJson['prompt'] = $this->prompt;
        }
        if ($this->negativePrompt) {
            $toJson['negative_prompt'] = $this->negativePrompt;
        }
        return json_encode($toJson);
    }
}