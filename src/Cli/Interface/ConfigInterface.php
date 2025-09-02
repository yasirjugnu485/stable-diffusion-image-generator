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

interface ConfigInterface
{
    /**
     * Echo initialize config
     *
     * @var string
     */
    const string ECHO_INIT_CONFIG = 'Initialize configuration';

    /**
     * Echo dry run is activated
     *
     * @var string
     */
    const string ECHO_DRY_RUN_IS_ACTIVATED = 'DryRun is activated';

    /**
     * Success initialize config
     *
     * @var string
     */
    const string SUCCESS_INIT_CONFIG = 'Successfully initialized configuration';

    /**
     * Error config not found
     *
     * @var string
     */
    const string ERROR_CONFIG_NOT_FOUND = 'config.php not found';

    /**
     * Error no host configured
     *
     * @var string
     */
    const string ERROR_NO_HOST_CONFIGURED = 'No host configured';

    /**
     * Error no width configured
     *
     * @var string
     */
    const string ERROR_NO_WIDTH_CONFIGURED = 'No width configured';

    /**
     * Error no height configured
     *
     * @var string
     */
    const string ERROR_NO_HEIGHT_CONFIGURED = 'No height configured';

    /**
     * Error no steps configured
     *
     * @var string
     */
    const string ERROR_NO_STEPS_CONFIGURED = 'No steps configured';

    /**
     * Error config not loaded
     *
     * @var string
     */
    const string ERROR_CONFIG_NOT_LOADED = 'config.php not loaded';
}