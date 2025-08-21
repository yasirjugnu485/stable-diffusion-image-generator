<?php

declare(strict_types=1);

namespace Interface;

interface ExecuteInterface
{
    const string ECHO_GENERATE_IMAGE = 'Generate image %1$s';

    const string ECHO_GENERATE_IMAGE_OF = 'Generate image %1$s of %2$s';

    const string ECHO_GENERATE_IMAGE_WITH_PROMPT = 'Call Stable Diffusion txt2img API to generating image with prompt "%1$s"';

    const string ECHO_GENERATE_IMAGE_WITH_PROMPT_AND_IMAGE = 'Call Stable Diffusion img2img API to generating image with prompt "%1$s" and image "%2$s"';

    const string ERROR_GENERATE_IMAGE_WITH_PROMPT = 'Error generating image with prompt "%1$s"';

    const string ERROR_GENERATE_IMAGE_WITH_PROMPT_AND_IMAGE = 'Error generating image with prompt "%1$s" and image "%2$s"';

    const string SUCCESS_SAVE = 'Successfully created %1$s images';

    const string SUCCESS_CALL = 'Successfully called stable diffusion to create %1$s images';

    const string SUCCESS_SAVE_IMAGE = 'Successfully save image to "%1$s"';
}