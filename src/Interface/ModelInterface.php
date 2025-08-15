<?php

declare(strict_types=1);

namespace Interface;

interface ModelInterface
{
    const string ECHO_GET_START_MODEL = 'Get start model';

    const string ECHO_INIT_MODELS = 'Initialize models';

    const string ECHO_RESTORE_START_MODEL = 'Restore start model "%1$s"';

    const string SUCCESS_GET_START_MODEL = 'Successfully get model "%1$s"';

    const string SUCCESS_INIT_MODELS = 'Successfully initialized models and saved to file "models.json"';

    const string SUCCESS_RESTORE_START_MODEL = 'Successfully restore model "%1$s"';

    const string ERROR_NO_MODELS_FOUND = 'No models found';

    const string ERROR_MODEL_NOT_FOUND = 'Model not found "%1$s"';
}