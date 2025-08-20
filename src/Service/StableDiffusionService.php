<?php

declare(strict_types=1);

namespace Service;

use Controller\ConfigController;
use Exception\StableDiffusionServiceException;

class StableDiffusionService
{
    public function getSdModels(): array|null
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $url = rtrim($config['host'], '/') . '/sdapi/v1/sd-models';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'content-type: application/json',
                'Connection: Keep-Alive'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $error = curl_error($curl);
        if ($error) {
            throw new StableDiffusionServiceException($error);
        }

        $models = json_decode($response, true);
        if (file_exists(__DIR__ . '/../../checkpoints.json')) {
            unlink(__DIR__ . '/../../checkpoints.json');
        }
        file_put_contents(
            __DIR__ . '/../../checkpoints.json',
            json_encode($models,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        );

        return $models;
    }

    public function getOptions(): array|null
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $url = rtrim($config['host'], '/') . '/sdapi/v1/options';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'content-type: application/json',
                'Connection: Keep-Alive'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $error = curl_error($curl);
        if ($error) {
            throw new StableDiffusionServiceException($error);
        }

        $options = json_decode($response, true);
        if (file_exists(__DIR__ . '/../../options.json')) {
            unlink(__DIR__ . '/../../options.json');
        }
        file_put_contents(
            __DIR__ . '/../../options.json',
            json_encode($options,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        );

        return $options;
    }

    public function setOptions(array|string $payload): string|null
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $url = rtrim($config['host'], '/') . '/sdapi/v1/options';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => is_array($payload) ? json_encode($payload, JSON_UNESCAPED_UNICODE) : $payload,
            CURLOPT_HTTPHEADER => [
                'content-type: application/json',
                'Connection: Keep-Alive'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $error = curl_error($curl);
        if ($error) {
            throw new StableDiffusionServiceException($error);
        }

        return $response;
    }

    public function callTxt2img(string $payload): string|null
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $url = rtrim($config['host'], '/') . '/sdapi/v1/txt2img';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'content-type: application/json',
                'Connection: Keep-Alive'
            ],
        ]);
        if (!$config['saveImages']) {
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        }

        $response = curl_exec($curl);
        curl_close($curl);

        $error = curl_error($curl);
        if ($error) {
            if (!$config['saveImages']) {
                return $response;
            }
            throw new StableDiffusionServiceException($error);
        }

        $array = json_decode($response, true);
        if (isset($array['error'])) {
            throw new StableDiffusionServiceException($response);
        }

        return $response;
    }

    public function callImg2img(string $payload): string|null
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $url = rtrim($config['host'], '/') . '/sdapi/v1/img2img';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'content-type: application/json',
                'Connection: Keep-Alive'
            ],
        ]);
        if (!$config['saveImages']) {
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        }

        $response = curl_exec($curl);
        curl_close($curl);

        $error = curl_error($curl);
        if ($error) {
            if (!$config['saveImages']) {
                return $response;
            }
            throw new StableDiffusionServiceException($error);
        }

        return $response;
    }

    public function getProgress(): array|null
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $url = rtrim($config['host'], '/') . '/sdapi/v1/progress?skip_current_image=true';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'content-type: application/json',
                'Connection: Keep-Alive'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $error = curl_error($curl);
        if ($error) {
            throw new StableDiffusionServiceException($error);
        }

        $progress = json_decode($response, true);

        return $progress['progress'] ? $progress : null;
    }
}