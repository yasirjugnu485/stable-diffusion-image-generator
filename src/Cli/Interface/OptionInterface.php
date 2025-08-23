<?php

declare(strict_types=1);

namespace Cli\Interface;

interface OptionInterface
{
    const string ECHO_INIT_OPTIONS = 'Initialize options';

    const string SUCCESS_INIT_OPTIONS = 'Successfully initialized options and saved to file "options.json"';
}