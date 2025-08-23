<?php

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Interface\OptionInterface;
use Cli\Service\StableDiffusionService;

class OptionController implements OptionInterface
{
    public static array|null $options = null;

    public function __construct()
    {
        if (self::$options === null) {
            $this->initOptions();
        }
    }

    private function initOptions(bool $silent = false): array
    {
        if (self::$options === null) {
            if (!$silent) {
                new EchoController(self::ECHO_INIT_OPTIONS);
            }

            $stableDiffusionService = new StableDiffusionService();
            self::$options = $stableDiffusionService->getOptions();

            if (!$silent) {
                new EchoController(self::SUCCESS_INIT_OPTIONS);
                new EchoController();
            }
        }

        return self::$options;
    }

    public function getOption(string $name): mixed
    {
        return $this->initOptions()[$name] ?? null;
    }
}