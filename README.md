# Stable Diffusion Image Creator
### Web GUI and CLI API Mass Image Generator, Prompt Merger, Initialize Images Merger, Lora Handler, Text 2 Image Mode, Image 2 Image Mode, Loop Mode, Images Albums, Stable Diffusion Data Inspector
***


## Description
### Welcome to Stable Diffusion Image Creator!


***


## Config

### The three configuration files:
- <b>config.inc.php</b>: This configuration file belongs to the GitLab repository and should never be modified.
  Copy the contents of the original config.inc.php configuration file into a new file named config.local.php for the CLI
  application.
- <b>config.local.php</b>: This configuration file is used by the CLI application. To customize it, copy the contents of 
  config.inc.php into this file. If this file is absent, the CLI will automatically load the default configuration from 
  config.inc.php.
- <b>config.web.php</b>: This configuration file is automatically created and managed by the web application. You should 
  not modify this file, as doing so could lead to issues with the web application's functionality. If you encounter 
  problems with the web application, delete this file so that it can be automatically recreated.

### CLI Custom configuration file:
Separate configuration files can be manually created for the command-line application. To do this, copy the 
config.inc.php file to another .php configuration file in the root directory and customize it as needed. Run the CLI 
application using the <b>--config MY-CUSTOM-CONFIG-FILE.php</b> parameter to use your custom configuration file.

### Configuration Parameters
- <b>$this->host </b><i>[string]</i>: The host and port where the stable diffusion API is running.
- <b>$this->startWebApplication </b><i>[bool]</i>: Start PHP Build-In server for the Web-Application.
- <b>$this->numberOfImages </b><i>[int, null]</i>: The number of images to generate. If set to null or 0, the script 
  will generate unlimited images.
- <b>$this->loop </b><i>[bool]</i>: Creates a txt2txt -> img2img or img2img -> img2img loop, depending on the mode option. 
  Creates the first image with the configured mode and all subsequent images as img2img with the generated image.  
  <i><b>Warning</b>: Will automatically enable saveImages if enabled.</i>
- <b>$this->dryRun </b><i>[bool]</i>: If true, only settings will be checked and no images will be created.
- <b>$this->mode </b><i>[string]</i>: The mode to use. Can be txt2img or img2img. If img2img is selected, the Stable 
- Diffusion img2img settings has to set correctly and the init images directory has to be set.
- <b>$this->checkpoint </b><i>[string, array, false, null]</i>: String of the checkpoint name, array from multiple 
  checkpoints, null for all checkpoints are used in turn, false for selected checkpoint.
- <b>$this->sampler </b><i>[string,array,null</i>]: String of the sampler name, array from multiple samplers, null for 
  default sampler (Euler).
- <b>$this->prompt </b><i>[string, null]</i>: The directory where the prompt generator files are located. A 
  subdirectory in prompt directory where prompt files for prompt merging are located. If set to null, the images wil be 
  generated with empty prompt.
- <b>$this->negativePrompt </b>[string, null]: The directory where the prompt generator files are located. A 
  subdirectory in prompt directory where prompt files for prompt merging are located. If set to null, the images wil be 
  generated with empty negative prompt.
- <b>$this->initImages </b><i>[string, null]</i>: The directory where the images to be initialized are located. A 
  subdirectory in init_images directory where initialize images are stored. Only available if mode is img2img.
- <b>$this->width </b><i>[int]: The width of the generated images.
- <b>$this->height </b><i>[int]: The height of the generated images.
- <b>$this->steps </b><i>[int]: The number of sampling steps to use for the generation.
- <b>$this->refinerCheckpoint </b><i>[string, array, null]: String of the refiner checkpoint name, array from multiple 
  refiner checkpoints, null for deactivate refiner checkpoint.
- <b>$this->refinerSwitchAt </b><i>[float]</i>: Float 0.0 - 1.0 when checkpoint switch to refiner checkpoint. Set 0.5 to 
  set checkpoint should switch to refiner checkpoint at 50% of image creation.
- <b>$this->restoreFaces </b><i>[bool]</i>: Option restore faces and remove artifacts.
- <b>$this->tiling </b><i>[bool]</i>: Tiling to generate seamless textures.
- <b>$this->lora </b><i>[string, array, null]</i>: String of the lora name, array from multiple loras, null for disable 
  lora.
- <b>$this->loraKeywords </b><i>[string, null]</i>: Keywords to trigger special trained action of loras.
- <b>$this->enableHr </b><i>[bool]</i>: Enable or disable hires fix. Only available in txt2img mode.
- <b>$this->hrUpscaler </b><i>[string, null]</i>: Upscaler type string for specified upscaler, null for default upscaler 
  (Latent). Only available if hires fix is enabled.
- <b>$this->hrResizeX </b><i>[int, null]</i>: Width of the resized image. Only available if hires fix is enabled and hr 
  scale is null.
- <b>$this->hrResizeY </b><i>[int, null]</i>: Height of the resized image. Only available if hires fix is enabled and hr 
  scale is null.
- <b>$this->hrScale </b><i>[float, null]</i>: Scale of the resized image. Only available if hires fix is enabled. 
  Overrides hr resize width and height if not null.
- <b>$this->hrSamplerName </b><i>[string, null]</i>: Sampler type for hires. Only available if hires fix is enabled. 
  Default sampler if null (Euler).
***