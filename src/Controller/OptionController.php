<?php

declare(strict_types=1);

namespace Controller;

use Interface\OptionInterface;
use Service\StableDiffusionService;

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

    public function setOptions(array $options): void
    {
        new EchoController(sprintf(self::ECHO_SET_OPTIONS, json_encode($options)));

        $stableDiffusionService = new StableDiffusionService();
        $stableDiffusionService->setOptions($options);
        $this->initOptions(true);

        new EchoController(self::SUCCESS_SET_OPTIONS);
    }

    public function getOptions(): array
    {
        return $this->initOptions();
    }

    public function getOption(string $name): mixed
    {
        return $this->initOptions()[$name] ?? null;
    }
}