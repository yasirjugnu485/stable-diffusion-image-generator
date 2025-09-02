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

interface SamplerInterface
{
    /**
     * Echo initialize samplers
     *
     * @var string
     */
    const string ECHO_INIT_SAMPLERS = 'Initialize samplers';

    /**
     * Success initialized samplers
     *
     * @var string
     */
    const string SUCCESS_INIT_SAMPLERS = 'Successfully initialized samplers and saved to file "samplers.json"';

    /**
     * Error no samplers found
     *
     * @var string
     */
    const string ERROR_NO_SAMPLERS_FOUND = 'No samplers found';

    /**
     * Error configured sampler not found
     *
     * @var string
     */
    const string ERROR_CONFIGURED_SAMPLER_NOT_FOUND = 'Configured sampler not found "%1$s"';
}