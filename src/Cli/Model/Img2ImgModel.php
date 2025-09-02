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

use Cli\Exception\PromptImageGeneratorException;

class Img2ImgModel extends BaseModel
{
    /**
     * Initialize image
     *
     * @var array|null
     */
    private array|null $initImages = null;

    /**
     * Constructor
     *
     * @throws PromptImageGeneratorException
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set prompt
     *
     * @param string $prompt Prompt
     * @return $this
     */
    public function setPrompt(string $prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    /**
     * Set negative prompt
     *
     * @param string $negativePrompt Negative prompt
     * @return $this
     */
    public function setNegativePrompt(string $negativePrompt): static
    {
        $this->negativePrompt = $negativePrompt;

        return $this;
    }

    /**
     * Set initialize image
     *
     * @param string|array $initImages Initialize image
     * @return $this
     */
    public function setInitImages(string|array $initImages): static
    {
        $this->initImages = is_string($initImages) ? [$initImages] : [array_values($initImages)[0]];

        return $this;
    }

    /**
     * Convert to JSON payload
     *
     * @return string
     */
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