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

interface InitImagesCollectorInterface
{
    /**
     * Error initialize images directory wrong name
     *
     * @var string
     */
    const string ERROR_INIT_IMAGES_DIRECTORY_WRONG_NAME = 'The Initialize Images Directory may only contain numbers, letters, _ and -';

    /**
     * Error initialize images directory exists
     *
     * @var string
     */
    const string ERROR_INIT_IMAGES_DIRECTORY_EXISTS = 'The Initialize Images Directory already exists';

    /**
     * Success initialize images directory created
     *
     * @var string
     */
    const string SUCCESS_INIT_IMAGES_DIRECTORY_CREATED = 'The Initialize Images Directory has been created';
}