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
     * Used data for dry run
     *
     * @var array|null
     */
    private static array|null $data = null;

    /**
     * Add data to dry run
     *
     * @param string $data Data
     * @return void
     */
    public function addData(string $data): void
    {
        if (self::$data === null) {
            self::$data = [];
        }

        $array = json_decode($data, true);
        if (isset($array['init_images'][0])) {
            $array['init_images'][0] = substr($array['init_images'][0], 0, 50) . '...';
        }

        self::$data[] = $array;
    }

    /**
     * Exit CLI application
     *
     * @return void
     */
    public function exit(): void
    {
        if (self::$data) {
            file_put_contents(ROOT_DIR . 'dry_run.json',
                json_encode(self::$data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }
}