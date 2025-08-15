<?php

declare(strict_types=1);

namespace Interface;

interface ConfigInterface
{
    const string ECHO_INIT_CONFIG = 'Initialize configuration';

    const string ECHO_DRY_RUN_IS_ACTIVATED = 'DryRun is activated';

    const string SUCCESS_INIT_CONFIG = 'Successfully initialized configuration';

    const string ERROR_CONFIG_NOT_FOUND = 'config.php not found';

    const string ERROR_NO_HOST_CONFIGURED = 'No host configured';

    const string ERROR_NO_NUMBER_OF_IMAGES_CONFIGURED = 'No number of images configured';

    const string ERROR_NO_WIDTH_CONFIGURED = 'No width configured';

    const string ERROR_NO_HEIGHT_CONFIGURED = 'No height configured';

    const string ERROR_NO_STEPS_CONFIGURED = 'No steps configured';

    const string ERROR_CONFIG_NOT_LOADED = 'config.php not loaded';
}