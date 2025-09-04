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

use Cli\Exception\PromptImageGeneratorException;
use Cli\Exception\StableDiffusionServiceException;
use Cli\Interface\SamplerInterface;
use Cli\Service\StableDiffusionService;

class SamplerController implements SamplerInterface
{
    /**
     * Sampler data
     *
     * @var array|null
     */
    private static array|null $samplerData = null;

    /**
     * Used samplers from config
     *
     * @var array|false|string[]|null
     */
    private static array|false|null $sampler = null;

    /**
     * LAst used sampler
     *
     * @var string|null
     */
    private static string|null $lastSampler = null;

    /**
     * Current sampler
     *
     * @var string|null
     */
    private static string|null $currentSampler = null;

    /**
     * Constructor
     *
     * @throws StableDiffusionServiceException
     * @throws PromptImageGeneratorException
     */
    public function __construct()
    {
        if (self::$samplerData === null) {
            $samplers = $this->initSamplers();
            if (!$samplers) {
                throw new StableDiffusionServiceException(self::ERROR_NO_SAMPLERS_FOUND);
            }

            $configController = new ConfigController();
            $config = $configController->getConfig();
            $sampler = $config['sampler'];
            if ($sampler !== false) {
                if (is_array($sampler)) {
                    self::$sampler = $sampler;
                } elseif (is_string($sampler)) {
                    self::$sampler = [$sampler];
                } elseif (is_null($sampler)) {
                    self::$sampler = [];
                    foreach ($samplers as $sampler) {
                        self::$sampler[] = $sampler['name'];
                    }
                }

                if (is_array(self::$sampler)) {
                    foreach (self::$sampler as $samplerName) {
                        foreach ($samplers as $sampler) {
                            if ($samplerName === $sampler['name']) {
                                continue 2;
                            }
                        }
                        throw new StableDiffusionServiceException(
                            sprintf(self::ERROR_CONFIGURED_SAMPLER_NOT_FOUND, $samplerName)
                        );
                    }
                }
            }
        }
    }

    /**
     * Initialize samplers data
     *
     * @param bool $silent Echo progress in console
     * @return array
     * @throws StableDiffusionServiceException
     */
    private function initSamplers(bool $silent = false): array
    {
        if (self::$samplerData === null) {
            if (!$silent) {
                new EchoController(self::ECHO_INIT_SAMPLERS);
            }

            $stableDiffusionService = new StableDiffusionService();
            self::$samplerData = $stableDiffusionService->getSamplers();

            if (!$silent) {
                new EchoController(self::SUCCESS_INIT_SAMPLERS);
                new EchoController();
            }
        }

        return self::$samplerData;
    }

    /**
     * Set next sampler
     *
     * @return void
     */
    public function setNextSampler(): void
    {
        if (is_array(self::$sampler)) {
            if (self::$currentSampler === null) {
                self::$currentSampler = self::$sampler[0];
            } else {
                self::$lastSampler = self::$currentSampler;

                self::$currentSampler = null;
                $next = false;
                foreach (self::$sampler as $sampler) {
                    if ($next) {
                        self::$currentSampler = $sampler;
                        break;
                    } elseif ($sampler === self::$lastSampler) {
                        $next = true;
                    }
                }
                if (self::$currentSampler === null) {
                    self::$currentSampler = self::$sampler[0];
                }
            }
        }
    }

    /**
     * Get sampler data
     *
     * @return array|null
     */
    public function getSamplerData(): array|null
    {
        return self::$samplerData;
    }

    /**
     * Get current sampler
     *
     * @return string|null
     */
    public function getCurrentSampler(): string|null
    {
        return self::$currentSampler;
    }
}