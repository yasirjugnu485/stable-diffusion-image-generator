<?php

declare(strict_types=1);

namespace Model;

use Controller\EchoController;

class Img2ImgModel extends BaseModel
{
    private array|null $initImages = null;

    public function __construct()
    {
        parent::__construct();
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

    public function setInitImages(string|array $initImages): static
    {
        $this->initImages = is_string($initImages) ? [$initImages] : [array_values($initImages)[0]];

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