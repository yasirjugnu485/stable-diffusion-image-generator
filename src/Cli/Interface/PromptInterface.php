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

interface PromptInterface
{
    /**
     * Echo start
     *
     * @var string
     */
    const string ECHO_START = 'Start loading prompt data';

    /**
     * Echo end
     *
     * @var string
     */
    const string ECHO_END = 'Prompt data successfully loaded';

    /**
     * Error no prompt directory found
     *
     * @var string
     */
    const string ERROR_NO_PROMPT_DIRECTORY_FOUND = 'No prompt directory found';

    /**
     * Error no prompt subdirectories found
     *
     * @var string
     */
    const string ERROR_NO_PROMPT_SUBDIRECTORIES_FOUND = 'No prompt subdirectories in prompt directory found';

    /**
     * Error no prompt data found
     *
     * @var string
     */
    const string ERROR_NO_PROMPT_DATA_FOUND = 'No prompt data found';

    /**
     * Error configured prompt not found
     *
     * @var string
     */
    const string ERROR_CONFIGURED_PROMPT_NOT_FOUND = 'Configured prompt not found';
}