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

interface PromptInterface
{
    /**
     * Error prompt merger directory wrong name
     *
     * @var string
     */
    const string ERROR_PROMPT_MERGER_DIRECTORY_WRONG_NAME = 'The Prompt Merger Directory may only contain numbers, letters, _ and -';

    /**
     * Error prompt merger directory exists
     *
     * @var string
     */
    const string ERROR_PROMPT_MERGER_DIRECTORY_EXISTS = 'The Prompt Merger Directory already exists';

    /**
     * Success prompt merger directory created
     *
     * @var string
     */
    const string SUCCESS_PROMPT_MERGER_DIRECTORY_CREATED = 'The Prompt Merger Directory has been created';

    /**
     * Error save prompt merger files
     *
     * @var string
     */
    const string ERROR_SAVE_PROMPT_MERGER_FILES = 'Error on saving Prompt Merger Files';

    /**
     * Success save prompt merger files
     *
     * @var string
     */
    const string SUCCESS_SAVE_PROMPT_MERGER_FILES = 'Prompt Merger Files saved successfully';

    /**
     * Error prompt merger file wrong name
     *
     * @var string
     */
    const string ERROR_PROMPT_MERGER_FILE_WRONG_NAME = 'The Prompt Merger File names may only contain numbers, letters, _ and -';

    /**
     * Error add prompt merger file
     *
     * @var string
     */
    const string ERROR_ADD_PROMPT_MERGER_FILE = 'Error on adding Prompt Merger File';

    /**
     * Error prompt merger file exists
     *
     * @var string
     */
    const string ERROR_PROMPT_MERGER_FILE_EXISTS = 'Prompt Merger File already exists';

    /**
     * Success add prompt merger file
     *
     * @var string
     */
    const string SUCCESS_ADD_PROMPT_MERGER_FILE = 'Prompt Merger File added successfully';

    /**
     * Error delete prompt merger file
     *
     * @var string
     */
    const string ERROR_DELETE_PROMPT_MERGER_FILE = 'Error on deleting Prompt Merger File';

    /**
     * Success delete prompt merger file
     *
     * @var string
     */
    const string SUCCESS_DELETE_PROMPT_MERGER_FILE = 'Prompt Merger File deleted successfully';

    /**
     * Error delete prompt merger directory
     *
     * @var string
     */
    const string ERROR_DELETE_PROMPT_MERGER_DIRECTORY = 'Error on deleting Prompt Merger Directory';

    /**
     * Success delete prompt merger directory
     *
     * @var string
     */
    const string SUCCESS_DELETE_PROMPT_MERGER_DIRECTORY = 'Prompt Merger Directory deleted successfully';
}