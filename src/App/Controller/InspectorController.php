<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace App\Controller;

class InspectorController
{
    /**
     * Get checkpoints.json
     *
     * @return string|null
     */
    public function getCheckpointsJson(): string|null
    {
        if (!file_exists(ROOT_DIR . 'checkpoints.json')) {
            return null;
        }

        $checkpoints = json_decode(file_get_contents(ROOT_DIR . 'checkpoints.json'), true);
        $json = [];
        foreach ($checkpoints as $checkpoint) {
            $json[$checkpoint['model_name']] = $checkpoint;
        }

        return json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Get samplers.json
     *
     * @return string|null
     */
    public function getSamplersJson(): string|null
    {
        if (!file_exists(ROOT_DIR . 'samplers.json')) {
            return null;
        }

        $samplers = json_decode(file_get_contents(ROOT_DIR . 'samplers.json'), true);
        $json = [];
        foreach ($samplers as $sampler) {
            $json[$sampler['name']] = $sampler;
        }

        return json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Get upscalers.json
     *
     * @return string|null
     */
    public function getUpscalersJson(): string|null
    {
        if (!file_exists(ROOT_DIR . 'upscalers.json')) {
            return null;
        }

        $upscalers = json_decode(file_get_contents(ROOT_DIR . 'upscalers.json'), true);
        $json = [];
        foreach ($upscalers as $upscaler) {
            $json[$upscaler['name']] = $upscaler;
        }

        return json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Get loras.json
     *
     * @return string|null
     */
    public function getLorasJson(): string|null
    {
        if (!file_exists(ROOT_DIR . 'loras.json')) {
            return null;
        }

        $loras = json_decode(file_get_contents(ROOT_DIR . 'loras.json'), true);
        $json = [];
        foreach ($loras as $lora) {
            $json[$lora['name']] = $lora;
        }

        return json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}