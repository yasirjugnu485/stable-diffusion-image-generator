<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace App\Service;

class StableDiffusionService
{
    /**
     * Get checkpoints
     *
     * @param string $host Host
     * @return array|null
     */
    public function getSdModels(string $host): array|false
    {
        $url = $host . '/sdapi/v1/sd-models';

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
            return false;
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
     * @param string $host Host
     * @return array|false
     */
    public function getSamplers(string $host): array|false
    {
        $url = $host . '/sdapi/v1/samplers';

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
            return false;
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
     * @param string $host Host
     * @return array|false
     */
    public function getOptions(string $host): array|false
    {
        $url = $host . '/sdapi/v1/options';

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
            return false;
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
     * Get upscalers
     *
     * @param string $host Host
     * @return array|false
     */
    public function getUpscalers(string $host): array|false
    {
        $url = $host . '/sdapi/v1/upscalers';

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
            return false;
        }

        $upscalers = json_decode($response, true);
        if (file_exists(ROOT_DIR . '/upscalers.json')) {
            unlink(ROOT_DIR . '/upscalers.json');
        }
        file_put_contents(
            ROOT_DIR . '/upscalers.json',
            json_encode($upscalers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return $upscalers;
    }

    /**
     * Get current progress state
     *
     * @param string $host Host
     * @return array|false
     */
    public function getProgress(string $host): array|false
    {
        $url = $host . '/sdapi/v1/progress';

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
            return false;
        }

        $progress = json_decode($response, true);

        return $progress['progress'] ? $progress : false;
    }
}