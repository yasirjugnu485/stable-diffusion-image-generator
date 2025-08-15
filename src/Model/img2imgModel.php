<?php

declare(strict_types=1);

namespace Model;

use Controller\ConfigController;

class img2imgModel
{
    private string|null $prompt = null;

    private string|null $negativePrompt = null;

    private array|null $initImages = null;

    private int $width;

    private int $height;

    private int $steps;

    private $restoreFaces = true;

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

    public function setInitImages(array $initImages): static
    {
        $this->initImages = [$initImages[rand(0, count($initImages) - 1)]];
        return $this;
    }

    public function toJson(): string
    {
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
        if ($this->initImages) {
            $toJson['init_images'] = $this->initImages;
        }
        return json_encode($toJson);
    }
}