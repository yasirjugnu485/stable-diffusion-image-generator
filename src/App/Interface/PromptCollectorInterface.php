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

interface PromptCollectorInterface
{
    /**
     * Error prompt directory wrong name
     *
     * @var string
     */
    const string ERROR_PROMPT_DIRECTORY_WRONG_NAME = 'The Prompt Merger Directory may only contain numbers, letters, _ and -';

    /**
     * Error prompt directory exists
     *
     * @var string
     */
    const string ERROR_PROMPT_DIRECTORY_EXISTS = 'The Prompt Merger Directory already exists';

    /**
     * Success prompt directory created
     *
     * @var string
     */
    const string SUCCESS_PROMPT_DIRECTORY_CREATED = 'The Prompt Merger Directory has been created';

    /**
     * Error save prompt files
     *
     * @var string
     */
    const string ERROR_SAVE_PROMPT_FILES = 'Error on saving prompt files';

    /**
     * Success save prompt files
     *
     * @var string
     */
    const string SUCCESS_SAVE_PROMPT_FILES = 'Prompt files saved successfully';

    /**
     * Error prompt file wrong name
     *
     * @var string
     */
    const string ERROR_PROMPT_FILE_WRONG_NAME = 'The Prompt Merger File names may only contain numbers, letters, _ and -';

    /**
     * Error add prompt file
     *
     * @var string
     */
    const string ERROR_ADD_PROMPT_FILE = 'Error on adding prompt file';

    /**
     * Error prompt file exists
     *
     * @var string
     */
    const string ERROR_PROMPT_FILE_EXISTS = 'Prompt file already exists';

    /**
     * Success add prompt file
     *
     * @var string
     */
    const string SUCCESS_ADD_PROMPT_FILE = 'Prompt file added successfully';

    /**
     * Error delete prompt file
     *
     * @var string
     */
    const string ERROR_DELETE_PROMPT_FILE = 'Error on deleting prompt file';

    /**
     * Success delete prompt file
     *
     * @var string
     */
    const string SUCCESS_DELETE_PROMPT_FILE = 'Prompt file deleted successfully';

    /**
     * Error delete prompt directory
     *
     * @var string
     */
    const string ERROR_DELETE_PROMPT_DIRECTORY = 'Error on deleting prompt directory';

    /**
     * Success delete prompt directory
     *
     * @var string
     */
    const string SUCCESS_DELETE_PROMPT_DIRECTORY = 'Prompt directory deleted successfully';
}