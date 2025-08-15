<?php

declare(strict_types=1);

namespace Interface;

interface PromptInterface
{
    const string ECHO_START = 'Start loading prompt data';

    const string ECHO_END = 'Prompt data successfully loaded';

    const string ERROR_NO_PROMPT_DIRECTORY_FOUND = 'No prompt directory found';

    const string ERROR_NO_PROMPT_SUBDIRECTORIES_FOUND = 'No prompt subdirectories in prompt directory found';

    const string ERROR_NO_PROMPT_DATA_FOUND = 'No prompt data found';

    const string ERROR_CONFIGURED_PROMPT_NOT_FOUND = 'Configured prompt not found';
}