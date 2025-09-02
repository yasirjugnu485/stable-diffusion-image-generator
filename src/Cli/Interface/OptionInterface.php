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

interface OptionInterface
{
    /**
     * Echo initialize options
     *
     * @var string
     */
    const string ECHO_INIT_OPTIONS = 'Initialize options';

    /**
     * Success initialized options
     *
     * @var string
     */
    const string SUCCESS_INIT_OPTIONS = 'Successfully initialized options and saved to file "options.json"';
}