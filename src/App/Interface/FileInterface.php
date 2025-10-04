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

interface FileInterface
{
    /**
     * Error delete image by type and date time
     *
     * @var string
     */
    const string ERROR_DELETE_BY_TYPE_AND_DIRECTORY = 'Error on deleting Image by directory';

    /**
     * Success delete image by type and date time
     *
     * @var string
     */
    const string SUCCESS_DELETE_BY_TYPE_AND_DIRECTORY = 'Successfully deleted Image by directory';

    /**
     * Error delete image
     *
     * @var string
     */
    const string ERROR_DELETE_IMAGE = 'Error on delete Image';

    /**
     * Success delete image
     *
     * @var string
     */
    const string SUCCESS_DELETE_IMAGE = 'Successfully deleted Image';
}