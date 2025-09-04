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
     * Error delete image
     *
     * @var string
     */
    const string ERROR_DELETE_IMAGE = 'Error on deleting Image';

    /**
     * Success delete image
     *
     * @var string
     */
    const string SUCCESS_DELETE_IMAGE = 'The Image has been deleted successfully';
}