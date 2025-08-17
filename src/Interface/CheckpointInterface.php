<?php

declare(strict_types=1);

namespace Interface;

interface CheckpointInterface
{
    const string ECHO_INIT_CHECKPOINTS = 'Initialize checkpoints';

    const string SUCCESS_INIT_CHECKPOINTS = 'Successfully initialized checkpoints and saved to file "checkpoints.json"';

    const string ERROR_NO_CHECKPOINTS_FOUND = 'No checkpoints found';

    const string ERROR_CHECKPOINT_NOT_FOUND = 'Checkpoint not found "%1$s"';
}