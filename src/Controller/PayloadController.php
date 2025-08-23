<?php

declare(strict_types=1);

namespace Controller;

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

        $file = 'outputs/';
        if ($config['loop']) {
            $file .= 'loop/' . $config['dateTime'] . '/payloads.json';
        } elseif ($config['mode'] === 'txt2txt') {
            $file .= 'txt2txt/' . $config['dateTime'] . '/payloads.json';
        } else {
            $file .= 'img2img/' . $config['dateTime'] . '/payloads.json';
        }

        file_put_contents($file, json_encode(self::$payload, JSON_PRETTY_PRINT));
    }
}