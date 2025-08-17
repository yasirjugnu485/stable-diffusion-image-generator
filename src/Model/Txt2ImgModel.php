<?php

declare(strict_types=1);

namespace Model;

use Controller\ConfigController;

class Txt2ImgModel extends BaseModel
{
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

    public function toJson(): string
    {
        return parent::toJson();
    }
}