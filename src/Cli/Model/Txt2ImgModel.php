<?php

declare(strict_types=1);

namespace Cli\Model;

class Txt2ImgModel extends BaseModel
{
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

    public function toJson(): string
    {
        $toJson = json_decode(parent::toJson(), true);

        if ($this->enableHr) {
            $toJson['enable_hr'] = $this->enableHr;
//            $toJson['hr_upscaler'] = $this->hrUpscaler;
//            $toJson['hr_sampler_name'] = $this->hrSamplerName;
//            if ($this->hrScale !== null) {
//                $toJson['hr_scale'] = $this->hrScale;
//            } else {
//                $toJson['hr_resize_x'] = $this->hrResizeX;
//                $toJson['hr_resize_y'] = $this->hrResizeY;
//            }
        }

        return json_encode($toJson);
    }
}