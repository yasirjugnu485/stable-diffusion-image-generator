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

use App\Controller\ConfigController;

class StableDiffusionService
{
    /**
     * Checkpoints
     *
     * @var array|null
     */
    public static array|null $sdModels = null;

    /**
     * Samplers
     *
     * @var array|null
     */
    public static array|null $samplers = null;

    /**
     * Upscalers
     *
     * @var array|null
     */
    public static array|null $upscalers = null;

    /**
     * Loras
     *
     * @var array|null
     */
    public static array|null $loras = null;

    /**
     * Get checkpoints
     *
     * @param string $host Host
     * @param bool $force Force reload
     * @return array|null
     */
    public function getSdModels(string $host, bool $force = false): array|false
    {
        if (self::$sdModels === null || $force) {
            self::$sdModels = null;

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

            self::$sdModels = json_decode($response, true);
            if (file_exists(ROOT_DIR . '/checkpoints.json')) {
                unlink(ROOT_DIR . '/checkpoints.json');
            }
            file_put_contents(
                ROOT_DIR . '/checkpoints.json',
                json_encode(self::$sdModels, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }

        return self::$sdModels;
    }

    /**
     * Get samplers
     *
     * @param string $host Host
     * @param bool $force Force reload
     * @return array|false
     */
    public function getSamplers(string $host, bool $force = false): array|false
    {
        if (self::$samplers === null || $force) {
            self::$samplers = null;

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

            self::$samplers = json_decode($response, true);
            if (file_exists(ROOT_DIR . '/samplers.json')) {
                unlink(ROOT_DIR . '/samplers.json');
            }
            file_put_contents(
                ROOT_DIR . '/samplers.json',
                json_encode(self::$samplers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }

        return self::$samplers;
    }

    /**
     * Get upscalers
     *
     * @param string $host Host
     * @param bool $force Force reload
     * @return array|false
     */
    public function getUpscalers(string $host, bool $force = false): array|false
    {
        if (self::$upscalers === null || $force) {
            self::$upscalers = null;

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

            self::$upscalers = json_decode($response, true);
            if (file_exists(ROOT_DIR . '/upscalers.json')) {
                unlink(ROOT_DIR . '/upscalers.json');
            }
            file_put_contents(
                ROOT_DIR . '/upscalers.json',
                json_encode(self::$upscalers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }

        return self::$upscalers;
    }

    /**
     * Get loras
     *
     * @param string $host Host
     * @param bool $force Force reload
     * @return array|null
     */
    public function getLoras(string $host, bool $force = false): array|false
    {
        if (self::$loras === null || $force) {
            self::$loras = null;

            $url = $host . '/sdapi/v1/loras';
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

            self::$loras = json_decode($response, true);
            if (file_exists(ROOT_DIR . 'loras.json')) {
                unlink(ROOT_DIR . '/loras.json');
            }
            file_put_contents(
                ROOT_DIR . '/loras.json',
                json_encode(self::$loras, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }

        return self::$loras;
    }

    /**
     * Refresh loras
     *
     * @param string $host Host
     * @return bool
     */
    public function refreshLoras(string $host): bool
    {
        $url = rtrim($host, '/') . '/sdapi/v1/refresh-loras';
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '',
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

        return true;
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
     * Ping
     *
     * @param string $host Host
     * @return bool
     */
    public function ping(string $host): bool
    {
        $url = $host . '/sdapi/v1/ping';
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
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

        return (bool)$response;
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

    /**
     * Interrupt
     *
     * @return bool
     */
    public function interrupt(): bool
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $url = rtrim($config['host'], '/') . '/sdapi/v1/interrupt';
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '',
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

        $array = json_decode($response, true);

        // TODO: Check results

        return true;
    }
}