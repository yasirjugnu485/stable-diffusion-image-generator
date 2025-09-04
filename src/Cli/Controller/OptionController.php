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

use Cli\Exception\StableDiffusionServiceException;
use Cli\Interface\OptionInterface;
use Cli\Service\StableDiffusionService;

class OptionController implements OptionInterface
{
    /**
     * Options
     *
     * @var array|null
     */
    public static array|null $options = null;

    /**
     * Constructor
     *
     * @return void
     * @throws StableDiffusionServiceException
     */
    public function __construct()
    {
        if (self::$options === null) {
            $this->initOptions();
        }
    }

    /**
     * Initialize options
     *
     * @param bool $silent Echo progress in console
     * @return array
     * @throws StableDiffusionServiceException
     */
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

    /**
     * Get options
     *
     * @param string $name Name
     * @return mixed
     * @throws StableDiffusionServiceException
     */
    public function getOption(string $name): mixed
    {
        return $this->initOptions()[$name] ?? null;
    }
}