<?php

declare(strict_types=1);

namespace Cli\Interface;

interface RefinerInterface
{
    const string ERROR_NO_CHECKPOINTS_FOUND = 'No checkpoints found';

    const string ERROR_REFINER_CHECKPOINT_NOT_FOUND = 'Refiner checkpoint not found "%1$s"';
}