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

interface BootstrapInterface
{
    /**
     * Echo start application
     *
     * @var string
     */
    const string ECHO_START_APPLICATION = 'Start Stable Diffusion Prompt Image Generator';

    /**
     * Echo start by
     *
     * @var string
     */
    const string ECHO_START_BY = 'By xtrose© Media Studio 2025';

    /**
     * Echo try start build in server
     *
     * @var string
     */
    const string ECHO_TRY_START_BUILD_IN_SERVER = 'Try starting build in webserver';

    /**
     * Success start build in server
     *
     * @var string
     */
    const string SUCCESS_START_BUILD_IN_SERVER = 'Successfully started build in webserver on "http://localhost:8000"';

    /**
     * Error start build in server
     *
     * @var string
     */
    const string ERROR_START_BUILD_IN_SERVER = 'Error on starting build in webserver';

    /**
     * Success start application
     *
     * @var string
     */
    const string SUCCESS_START_APPLICATION = 'Successfully started application';

    /**
     * Error unknown argument
     *
     * @var string
     */
    const string ERROR_UNKNOWN_ARGUMENT = 'Unknown argument "%1$s" type in "php run.php --help" for help';

    /**
     * Error unknown config
     *
     * @var string
     */
    const string ERROR_UNKNOWN_CONFIG = 'Unknown file for argument --config or -c use "--config [FILE]" type in "php run.php --help" for help';

    /**
     * Error custom config not found
     *
     * @var string
     */
    const string ERROR_CUSTOM_CONFIG_NOT_FOUND = 'Custom config file "%1$s" not found';
}