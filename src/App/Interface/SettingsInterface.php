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

interface SettingsInterface
{
    /**
     * Error save settings
     *
     * @var string
     */
    const string ERROR_SAVE_SETTINGS = 'Error on save Settings';

    /**
     * Error save settings host is not an valid URL
     *
     * @var string
     */
    const string ERROR_SAVE_SETTINGS_HOST_IS_NOT_AN_VALID_URL = 'Error on save Settings. Host %1$s is not an valid URL';

    /**
     * Error save settings host not reachable
     *
     * @var string
     */
    const string ERROR_SAVE_SETTINGS_HOST_NOT_REACHABLE = 'Error on save Settings. Host %1$s not reachable.';

    /**
     * Success save settings
     *
     * @var string
     */
    const string SUCCESS_SAVE_SETTINGS = 'The Settings has been saved successfully';
}