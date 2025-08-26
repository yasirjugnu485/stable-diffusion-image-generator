<?php

declare(strict_types=1);

namespace Cli\Controller;

class PayloadController
{
    private static array $payload = [];

    public function add(string $file, string $mode, string $payload, string|null $img2imgFile = null): void
    {
        $payload = json_decode($payload, true);
        if (isset($payload['init_images'])) {
            unset($payload['init_images']);
        }
        if (null !== $img2imgFile) {
            $payload['init_images'] = $img2imgFile;
        }

        self::$payload[] = [
            'file' => $file,
            'mode' => $mode,
            'payload' => $payload,
        ];;

        $this->save();
    }

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

        $file = $directory . '/payloads.json';

        file_put_contents($file, json_encode(self::$payload, JSON_PRETTY_PRINT));
    }
}