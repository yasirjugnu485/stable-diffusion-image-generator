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
     * @var array|false|null
     */
    private static array|false|null $lora = null;

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
                } elseif (is_string($lora) && trim($lora)) {
                    self::$lora = [$lora];
                }
                if (is_array(self::$lora)) {
                    foreach (self::$lora as $loraAlias) {
                        foreach ($loras as $lora) {
                            if ($loraAlias === $lora['alias']) {
                                continue 2;
                            }
                        }
                        throw new StableDiffusionServiceException(
                            sprintf(self::ERROR_LORA_NOT_FOUND, $loraAlias)
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
     * Get lora string
     *
     * @return string
     */
    public function getLoraString(): string
    {
        if (self::$lora === null) {
            return '';
        }

        $loraString = '';
        foreach (self::$lora as $loraName) {
            foreach (self::$loraData as $lora) {
                if ($loraName === $lora['alias']) {
                    $loraString .= '<lora:' . $lora['alias'] . ':1>';
                    continue 2;
                }
            }
        }

        return ' ' . $loraString;
    }
}