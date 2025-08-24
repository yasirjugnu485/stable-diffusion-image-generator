<?php

declare(strict_types=1);

namespace Cli\Controller;

use DateTime;

class LogController
{
    public static array $logs = [];

    public static bool $initialized = false;

    public function __construct(string $message)
    {
        if (!$message) {
            return;
        }

        $date = new DateTime('NOW');
        $message = $date->format('Y-m-d H:i:s') . ' ' . $message;

        if (!is_dir(ROOT_DIR . 'var/log')) {
            mkdir(ROOT_DIR . 'var/log', 0777, true);
        }

        if (!ConfigController::$initialized) {
            self::$logs[] = $message;
        } elseif (!self::$initialized) {
            $configController = new ConfigController();
            $config = $configController->getConfig();
            file_put_contents(ROOT_DIR . 'var/log/' . $config['dateTime'] . '.log',
                implode(PHP_EOL, self::$logs) . PHP_EOL, FILE_APPEND
            );
            self::$initialized = true;
        } else {
            $configController = new ConfigController();
            $config = $configController->getConfig();
            file_put_contents(ROOT_DIR . 'var/log/' .
                $config['dateTime'] . '.log',
                $date->format('Y-m-d H:i:s') . ' ' . $message . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }
}