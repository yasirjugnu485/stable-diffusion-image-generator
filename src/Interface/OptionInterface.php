<?php

declare(strict_types=1);

namespace Interface;

interface OptionInterface
{
    const string ECHO_INIT_OPTIONS = 'Initialize options';

    const string ECHO_SET_OPTIONS = 'Set options "%1$s"';

    const string SUCCESS_INIT_OPTIONS = 'Successfully initialized options and saved to file "options.json"';

    const string SUCCESS_SET_OPTIONS = 'Successfully set options';
}