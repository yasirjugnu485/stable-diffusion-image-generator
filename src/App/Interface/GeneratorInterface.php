<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace App\Interface;

interface GeneratorInterface
{
    /**
     * Error on loading config
     *
     * @var string
     */
    const string ERROR_ON_LOAD_CONFIG = 'Error on configuration. Check your config.app.php, config.local.php and config.inc.php files and configure an reachable Stable Diffusion host.';

    /**
     * Error on load checkpoints
     *
     * @var string
     */
    const string ERROR_ON_LOAD_CHECKPOINTS = 'Error on load checkpoints. Stable Diffusion host ist not reachable. Check your config.app.php file and configure an reachable Stable Diffusion host.';

    /**
     * Error on load Samplers
     *
     * @var string
     */
    const string ERROR_ON_LOAD_SAMPLERS = 'Error on load samplers. Stable Diffusion host ist not reachable. Check your config.app.php file and configure an reachable Stable Diffusion host.';

    /**
     * Error on load prompts
     *
     * @var string
     */
    const string ERROR_ON_LOAD_PROMPTS = 'No Prompt Merger Directories available. Use Prompt Merger to create your prompt generator.';
}