<?php

declare(strict_types=1);

namespace Cli\Interface;

interface BootstrapInterface
{
    const string ECHO_START_APPLICATION = 'Start Stable Diffusion Prompt Image Generator';

    const string ECHO_START_BY = 'By xtrose© Media Studio 2025';

    const string ECHO_TRY_START_BUILD_IN_SERVER = 'Try starting build in webserver';

    const string SUCCESS_START_BUILD_IN_SERVER = 'Successfully started build in webserver on "http://localhost:8000"';

    const string ERROR_START_BUILD_IN_SERVER = 'Error on starting build in webserver';

    const string SUCCESS_START_APPLICATION = 'Successfully started application';
}