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

interface RefinerInterface
{
    /**
     * Error no checkpoints found
     *
     * @var string
     */
    const string ERROR_NO_CHECKPOINTS_FOUND = 'No checkpoints found';

    /**
     * Error refiner checkpoint not found
     *
     * @var string
     */
    const string ERROR_REFINER_CHECKPOINT_NOT_FOUND = 'Refiner checkpoint not found "%1$s"';
}