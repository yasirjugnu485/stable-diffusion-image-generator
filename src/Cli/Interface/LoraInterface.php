<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace Cli\Interface;

interface LoraInterface
{
    /**
     * Echo initialize loras
     *
     * @var string
     */
    const string ECHO_INIT_LORAS = 'Initialize loras';

    /**
     * Success init loras
     *
     * @var string
     */
    const string SUCCESS_INIT_LORAS = 'Successfully initialized loras and saved to file "loras.json"';

    /**
     * Error no loras found
     *
     * @var string
     */
    const string ERROR_NO_LORAS_FOUND = 'No loras found';

    /**
     * Error lora not found
     *
     * @var string
     */
    const string ERROR_LORA_NOT_FOUND = 'Lora not found "%1$s"';
}