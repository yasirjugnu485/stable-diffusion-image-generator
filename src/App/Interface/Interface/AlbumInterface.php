<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace App\Interface\Interface;

interface AlbumInterface
{
    /**
     * Error add sub album
     *
     * @var string
     */
    const string ERROR_ADD_SUB_ALBUM = 'Error on adding Sub-Album';

    /**
     * Error add sub album wrong name
     *
     * @var string
     */
    const string ERROR_ADD_SUB_ALBUM_WRONG_NAME = 'The Sub_Album name may only contain numbers, letters, _ and -';

    /**
     * Error add sub album exists
     *
     * @var string
     */
    const string ERROR_ADD_SUB_ALBUM_EXISTS = 'The Sub_Album already exists';

    /**
     * Success add sub album
     *
     * @var string
     */
    const string SUCCESS_ADD_SUB_ALBUM = 'Sub-Album added successfully';

    /**
     * Error delete album
     *
     * @var string
     */
    const string ERROR_DELETE_ALBUM = 'Error on deleting Album';

    /**
     * Success delete album
     *
     * @var string
     */
    const string SUCCESS_DELETE_ALBUM = 'Album deleted successfully';

    /**
     * Error copy entry
     *
     * @var string
     */
    const string ERROR_COPY_ENTRY = 'Error on copying Entry';

    /**
     * Success copy entry
     *
     * @var string
     */
    const string SUCCESS_COPY_ENTRY = 'Entry copied successfully';
}