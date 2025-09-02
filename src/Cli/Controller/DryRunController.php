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

class DryRunController
{
    /**
     * Used payloads for dry run
     *
     * @var array|null
     */
    private static array|null $payloads = null;

    /**
     * Add payload to dry run
     *
     * @param string $payload Payload
     * @return void
     */
    public function addPayload(string $payload): void
    {
        if (self::$payloads === null) {
            self::$payloads = [];
        }

        $array = json_decode($payload, true);
        if (isset($array['init_images'][0])) {
            $array['init_images'][0] = substr($array['init_images'][0], 0, 50) . '...';
        }

        self::$payloads[] = $array;
    }

    /**
     * Exit CLI application
     *
     * @return void
     */
    public function exit(): void
    {
        if (self::$payloads) {
            file_put_contents(
                ROOT_DIR . 'dry_run.json',
                json_encode(self::$payloads, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            );
        }
    }
}