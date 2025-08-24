<?php

declare(strict_types=1);

namespace Cli\Controller;

class DryRunController
{
    private static array|null $payloads = null;

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

    public function exit(): void
    {
        if (self::$payloads) {
            file_put_contents(
                ROOT_DIR . 'dry_run.json',
                json_encode(self::$payloads, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)
            );
        }
    }
}