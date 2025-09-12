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

interface InitImagesInterface
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

    /**
     * Error delete initialize images directory
     *
     * @var string
     */
    const string ERROR_DELETE_INIT_IMAGES_DIRECTORY = 'Error on deleting Initialize Images Directory';

    /**
     * Success delete initialize images directory
     *
     * @var string
     */
    const string SUCCESS_DELETE_INIT_IMAGES_DIRECTORY = 'Initialize Images Directory deleted successfully';

    /**
     * Error add initialize images image
     *
     * @var string
     */
    const string ERROR_ADD_INIT_IMAGES_IMAGE = 'Error on adding Initialize Images Image';

    /**
     * Error initialize images image wrong name
     *
     * @var string
     */
    const string ERROR_INIT_IMAGES_IMAGE_WRONG_NAME = 'The Initialize Images Image names may only contain numbers, letters, _ and -';

    /**
     * Error initialize images image wrong file extension
     *
     * @var string
     */
    const string ERROR_INIT_IMAGES_IMAGE_WRONG_FILE = 'Initialize Images Image hast wrong file extension (.png, .jpg, .jpeg)';

    /**
     * Error initialize images image exists
     *
     * @var string
     */
    const string ERROR_INIT_IMAGES_IMAGE_EXISTS = 'Initialize Images Image name already exists';

    /**
     * Error initialize images name duplicated values
     *
     * @var string
     */
    const string ERROR_INIT_IMAGES_NAME_DUPLICATED = 'Error the Initialize Images names has duplicated values';

    /**
     * Success add initialize images image
     *
     * @var string
     */
    const string SUCCESS_ADD_INIT_IMAGES_FILE = 'Initialize Images Image added successfully';

    /**
     * Error delete initialize images image
     *
     * @var string
     */
    const string ERROR_DELETE_INITIALIZE_IMAGES_IMAGE = 'Error on deleting Initialize Images Image';

    /**
     * Success delete initialize images image
     *
     * @var string
     */
    const string SUCCESS_DELETE_INITIALIZE_IMAGES_IMAGE = 'Initialize Images Image deleted successfully';

    /**
     * Error save initialize images images
     *
     * @var string
     */
    const string ERROR_SAVE_INITIALIZE_IMAGES_IMAGES = 'Error on saving Initialize Images Images';

    /**
     * Success save initialize images images
     *
     * @var string
     */
    const string SUCCESS_SAVE_INITIALIZE_IMAGES_IMAGES = 'Initialize Images Images saved successfully';

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