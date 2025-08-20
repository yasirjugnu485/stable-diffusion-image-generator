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
        if ($this->enableHr) {
            $toJson['script_name'] = 'SD upscale';
            $toJson['alwayson_scripts'] = [];
            $toJson['enable_hr'] = $this->enableHr;
            $toJson['hr_upscaler'] = $this->hrUpscaler;
            $toJson['hr_sampler_name'] = $this->hrSamplerName;
            if ($this->hrScale !== null) {
                $toJson['script_args'] = [null, 64, $this->hrUpscaler, $this->hrScale];
            } else {
                $percentX = 100 / $this->width * $this->hrResizeX;
                $percentY = 100 / $this->height * $this->hrResizeY;
                $percent = ($percentX + $percentY) / 2;
                $toJson['script_args'] = [
                    null,
                    64,
                    $this->hrUpscaler,
                    $percent <= 100 ? 2 : $percent / 100
                ];
            }
        }

        return json_encode($toJson);
    }
}