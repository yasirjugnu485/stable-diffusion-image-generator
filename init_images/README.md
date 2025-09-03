# Init Images
***


## Description
For the img2img creation mode create a subdirectory with your [DIRECTORY_NAME] here and place the init images there.

Configure in config.local.php:  
- &#36;this->mode = 'img2img'
- &#36;this->initImages = '[DIRECTORY_NAME]'

Please ensure that the [DIRECTORY_NAME] does not contain any spaces.  
The Stable Diffusion Image Generator supports .png and .jpg files to create images through img2img.  
For optimal results, use images with dimensions matching the desired output size.

Init images are best managed with the web application
***