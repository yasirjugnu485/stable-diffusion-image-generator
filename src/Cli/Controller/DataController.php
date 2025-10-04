<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Exception\PromptImageGeneratorException;

class DataController
{
    /**
     * Collected data
     *
     * @var array
     */
    private static array $data = [];

    /**
     * Add to data
     *
     * @param string $file File
     * @param string $mode Mode
     * @param string $data Data
     * @param string|null $img2imgFile Img2img file
     * @return void
     * @throws PromptImageGeneratorException
     */
    public function add(string $file, string $mode, string $data, string|null $img2imgFile = null): void
    {
        $data = json_decode($data, true);
        if (isset($data['init_images'])) {
            unset($data['init_images']);
        }
        if (null !== $img2imgFile) {
            $data['init_images'] = str_replace(ROOT_DIR, '', $img2imgFile);
        }

        self::$data[] = [
            'file' => str_replace(ROOT_DIR, '', $file),
            'mode' => $mode,
            'data' => $data,
        ];;

        $this->save();
    }

    /**
     * Save collected data
     *
     * @return void
     * @throws PromptImageGeneratorException
     */
    private function save(): void
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $directory = ROOT_DIR . 'outputs/';
        if ($config['loop']) {
            $directory .= 'loop/' . $config['dateTime'];
        } elseif ($config['mode'] === 'txt2img') {
            $directory .= 'txt2img/' . $config['dateTime'];
        } else {
            $directory .= 'img2img/' . $config['dateTime'];
        }
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $file = $directory . '/data.json';

        file_put_contents($file, json_encode(self::$data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}