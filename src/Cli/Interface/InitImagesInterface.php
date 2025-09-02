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

interface InitImagesInterface
{
    /**
     * Echo initialize init images data
     *
     * @var string
     */
    const string ECHO_INIT_INIT_IMAGES = 'Initialize init images data';

    /**
     * Success initialize init images data
     *
     * @var string
     */
    const string SUCCESS_INIT_INIT_IMAGES = 'Successfully initialized init images data';

    /**
     * Error no init images directory found
     *
     * @var string
     */
    const string ERROR_NO_INIT_IMAGES_DIRECTORY_FOUND = 'No init_images directory found';

    /**
     * Error no init images subdirectories found
     *
     * @var string
     */
    const string ERROR_NO_INIT_IMAGES_SUBDIRECTORIES_FOUND = 'No init_images subdirectories in init_images directory found';

    /**
     * Error no init images data found
     *
     * @var string
     */
    const string ERROR_NO_INIT_IMAGES_DATA_FOUND = 'No init_images data found';

    /**
     * Error configured init images not found
     *
     * @var string
     */
    const string ERROR_CONFIGURED_INIT_IMAGES_NOT_FOUND = 'Configured init_images not found';

    /**
     * Error PHP GD extension is missing
     *
     * @var string
     */
    const string ERROR_PHP_GD_MISSING = 'PHP GD extension is missing';
}