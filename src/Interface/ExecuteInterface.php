<?php

declare(strict_types=1);

namespace Interface;

interface ExecuteInterface
{
    const string ECHO_GENERATE_IMAGE = 'Generate image %1$s';

    const string ECHO_GENERATE_IMAGE_OF = 'Generate image %1$s of %2$s';

    const string ECHO_GENERATE_IMAGE_WITH_PROMPT = 'Call Stable Diffusion API and generate image with prompt "%1$s"';

    const string ECHO_GENERATE_IMAGE_WITH_PROMPT_AND_IMAGES = 'Generate generating image with prompt "%1$s" and images "%2$s"';

    const string ERROR_GENERATE_IMAGE_WITH_PROMPT = 'Error generating image with prompt "%1$s"';

    const string ERROR_GENERATE_IMAGE_WITH_PROMPT_AND_IMAGES = 'Error generating image with prompt "%1$s" and images "%2$s"';

    const string SUCCESS_SAVE = 'Successfully created %1$s images';

    const string SUCCESS_CALL = 'Successfully called stable diffusion to create %1$s images';

    const string SUCCESS_SAVE_IMAGE_WITH_PROMPT = 'Successfully save image %1$s with prompt "%2$s"';
}