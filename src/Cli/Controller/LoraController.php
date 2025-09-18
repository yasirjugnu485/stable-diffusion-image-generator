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
use Cli\Interface\LoraInterface;
use Cli\Service\StableDiffusionService;

class LoraController implements LoraInterface
{
    /**
     * Lora data
     *
     * @var array|null
     */
    private static array|null $loraData = null;

    /**
     * Lora from config
     *
     * @var array|false|string[]|null
     */
    private static array|false|null $lora = false;

    /**
     * Last used lora
     *
     * @var string|null
     */
    private static string|null $lastLora = null;

    /**
     * Current lora
     *
     * @var string|null
     */
    private static string|null $currentLora = null;

    /**
     * Constructor
     *
     * @throws StableDiffusionServiceException
     * @throws PromptImageGeneratorException
     */
    public function __construct()
    {
        if (self::$loraData === null) {
            $loras = $this->initLoras();
            if (!$loras) {
                throw new StableDiffusionServiceException(self::ERROR_NO_LORAS_FOUND);
            }

            $configController = new ConfigController();
            $config = $configController->getConfig();
            $lora = $config['lora'];
            if ($lora !== false) {
                if (is_array($lora)) {
                    self::$lora = $lora;
                } elseif (is_string($lora)) {
                    self::$lora = [$lora];
                } elseif (is_null($lora)) {
                    self::$lora = [];
                    foreach ($loras as $lora) {
                        self::$lora[] = $lora['name'];
                    }
                }

                if (is_array(self::$lora)) {
                    foreach (self::$lora as $loraName) {
                        foreach ($loras as $lora) {
                            if ($loraName === $lora['name']) {
                                continue 2;
                            }
                        }
                        throw new StableDiffusionServiceException(
                            sprintf(self::ERROR_LORA_NOT_FOUND, $loraName)
                        );
                    }
                }
            }
        }
    }

    /**
     * Initialize loras data
     *
     * @param bool $silent Echo progress in console
     * @return array
     * @throws StableDiffusionServiceException|PromptImageGeneratorException
     */
    private function initLoras(bool $silent = false): array
    {
        if (self::$loraData === null) {
            if (!$silent) {
                new EchoController(self::ECHO_INIT_LORAS);
            }

            $stableDiffusionService = new StableDiffusionService();
            self::$loraData = $stableDiffusionService->getLoras();

            if (!$silent) {
                new EchoController(self::SUCCESS_INIT_LORAS);
                new EchoController();
            }
        }

        return self::$loraData;
    }

    /**
     * Set next lora
     *
     * @return void
     */
    public function setNextLora(): void
    {
        if (is_array(self::$lora)) {
            if (self::$currentLora === null) {
                self::$currentLora = self::$lora[0];
            } else {
                self::$lastLora = self::$currentLora;

                self::$currentLora = null;
                $next = false;
                foreach (self::$lora as $lora) {
                    if ($next) {
                        self::$currentLora = $lora;
                        break;
                    } elseif ($lora === self::$lastLora) {
                        $next = true;
                    }
                }
                if (self::$currentLora === null) {
                    self::$currentLora = self::$lora[0];
                }
            }
        }
    }

    /**
     * Get current lora
     *
     * @return string|null
     */
    public function getCurrentLora(): string|null
    {
        return self::$currentLora;
    }
}