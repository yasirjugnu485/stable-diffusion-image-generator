<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace Shared\Service;

use Cli\Controller\ConfigController;
use Cli\Exception\PromptImageGeneratorException;
use Cli\Exception\StableDiffusionServiceException;

class StableDiffusionService
{
    /**
     * Get checkpoints
     *
     * @return array|null
     * @throws StableDiffusionServiceException
     * @throws PromptImageGeneratorException
     */
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
        if (file_exists(ROOT_DIR . '/checkpoints.json')) {
            unlink(ROOT_DIR . '/checkpoints.json');
        }
        file_put_contents(
            ROOT_DIR . '/checkpoints.json',
            json_encode($models, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return $models;
    }

    /**
     * Get samplers
     *
     * @return array|null
     * @throws PromptImageGeneratorException
     * @throws StableDiffusionServiceException
     */
    public function getSamplers(): array|null
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $url = rtrim($config['host'], '/') . '/sdapi/v1/samplers';

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

        $samplers = json_decode($response, true);
        if (file_exists(ROOT_DIR . '/samplers.json')) {
            unlink(ROOT_DIR . '/samplers.json');
        }
        file_put_contents(
            ROOT_DIR . '/samplers.json',
            json_encode($samplers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return $samplers;
    }

    /**
     * Get options
     *
     * @return array|null
     * @throws PromptImageGeneratorException
     * @throws StableDiffusionServiceException
     */
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
        if (file_exists(ROOT_DIR . '/options.json')) {
            unlink(ROOT_DIR . '/options.json');
        }
        file_put_contents(
            ROOT_DIR . '/options.json',
            json_encode($options, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return $options;
    }

    /**
     * Set options
     *
     * @param array|string $payload Payload
     * @return string|null
     * @throws PromptImageGeneratorException
     * @throws StableDiffusionServiceException
     */
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

    /**
     * Call Stable Diffusion txt2img API
     *
     * @param string $payload Payload
     * @return string|null
     * @throws PromptImageGeneratorException
     * @throws StableDiffusionServiceException
     */
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

    /**
     * Call Stable Diffusion img2img API
     *
     * @param string $payload Payload
     * @return string|null
     * @throws PromptImageGeneratorException
     * @throws StableDiffusionServiceException
     */
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

    /**
     * Get current progress state
     *
     * @return array|null
     * @throws PromptImageGeneratorException
     * @throws StableDiffusionServiceException
     */
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