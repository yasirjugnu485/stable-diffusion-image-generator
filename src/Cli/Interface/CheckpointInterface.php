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

interface CheckpointInterface
{
    /**
     * Echo initialize checkpoints
     *
     * @var string
     */
    const string ECHO_INIT_CHECKPOINTS = 'Initialize checkpoints';

    /**
     * Success init checkpoints
     *
     * @var string
     */
    const string SUCCESS_INIT_CHECKPOINTS = 'Successfully initialized checkpoints and saved to file "checkpoints.json"';

    /**
     * Error no checkpoints found
     *
     * @var string
     */
    const string ERROR_NO_CHECKPOINTS_FOUND = 'No checkpoints found';

    /**
     * Error checkpoint not found
     *
     * @var string
     */
    const string ERROR_CHECKPOINT_NOT_FOUND = 'Checkpoint not found "%1$s"';
}