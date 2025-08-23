<?php

declare(strict_types=1);

namespace Cli\Interface;

interface SamplerInterface
{
    const string ECHO_INIT_SAMPLERS = 'Initialize samplers';

    const string SUCCESS_INIT_SAMPLERS = 'Successfully initialized samplers and saved to file "samplers.json"';

    const string ERROR_NO_SAMPLERS_FOUND = 'No samplers found';

    const string ERROR_CONFIGURED_SAMPLER_NOT_FOUND = 'Configured sampler not found "%1$s"';
}