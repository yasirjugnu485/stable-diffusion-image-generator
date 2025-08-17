<?php

declare(strict_types=1);

namespace Model;

use Controller\ConfigController;

class Img2ImgModel extends BaseModel
{
    private array|null $initImages = null;

    public function __construct()
    {
        parent::__construct();

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
        $random = rand(0, count($initImages) - 1);
        $this->initImages = [$initImages[$random]];
        return $this;
    }

    public function toJson(): string
    {
        $toJson = json_decode(parent::toJson(), true);
        if ($this->initImages) {
            $toJson['init_images'] = $this->initImages;
        }

        return json_encode($toJson);
    }
}