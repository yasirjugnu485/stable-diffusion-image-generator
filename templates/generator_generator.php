<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (!isset($params['error']) || !$params['error']) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12 text-dark">

                <h4 class="mb-3">
                    Image Generator
                </h4>

                <form method="post"
                      id="form">
                    <input type="hidden"
                           name="action"
                           id="action"
                           value="generate">

                    <div class="card mb-4">
                        <div class="card-header bg-dark text-light">
                            <h5 class="mt-2">
                                Default Settings
                            </h5>
                        </div>
                        <div class="card-body text-dark">
                            <div class="row">

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="host"
                                           class="form-label">
                                        Host
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="host"
                                           value="<?php echo $params['config']['host']; ?>"
                                           disabled>
                                    <div class="form-text text-warning">
                                        The Host can only be configured at the settings.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="numberOfImages"
                                           class="form-label">
                                        Number of images to be created
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="numberOfImages"
                                           name="numberOfImages"
                                           min="0"
                                           max="1000000"
                                           value="<?php echo $params['config']['numberOfImages']; ?>"
                                           onchange="generator.numberOfImagesOnchange()">
                                    <div class="form-text">
                                        The number of images to generate. If set to null or 0, the script will generate
                                        unlimited images.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="loop"
                                           class="form-label">
                                        Use Loop function
                                    </label>
                                    <select class="form-select"
                                            id="loop"
                                            name="loop">
                                        <option value="0"
                                            <?php if (!$params['config']['loop']) {
                                                echo 'selected';
                                            } ?>>
                                            false
                                        </option>
                                        <option value="1"
                                            <?php if ($params['config']['loop']) {
                                                echo 'selected';
                                            } ?>>
                                            true
                                        </option>
                                    </select>
                                    <div class="form-text">
                                        Creates a txt2txt -> img2img or img2img -> img2img loop, depending on the mode
                                        option.
                                        Creates the first image with the configured mode and all subsequent images as
                                        img2img
                                        with the generated image.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="mode"
                                           class="form-label">
                                        Mode
                                    </label>
                                    <select class="form-select"
                                            id="mode"
                                            name="mode"
                                            onchange="generator.modeOnchange();">
                                        <option value="txt2img"
                                            <?php if ($params['config']['mode'] === 'txt2img') {
                                                echo 'selected';
                                            } ?>>
                                            txt2img
                                        </option>
                                        <option value="img2img"
                                            <?php if ($params['config']['mode'] === 'img2img') {
                                                echo 'selected';
                                            } ?>>
                                            img2img
                                        </option>
                                    </select>
                                    <div class="form-text">
                                        The Mode to use. Can be txt2img or img2img. If img2img is selected, the Stable
                                        Diffusion
                                        img2img settings has to set correctly and the init images directory has to be
                                        set.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="checkpoint"
                                           class="form-label">
                                        Checkpoints (Models)
                                    </label>
                                    <select name="checkpoint[]"
                                            id="checkpoint"
                                            multiple>
                                        <?php
                                        foreach ($params['checkpoints'] as $checkpoint) {
                                            ?>
                                            <option value="<?php echo $checkpoint['name']; ?>"
                                                <?php if ($checkpoint['selected']) {
                                                    echo ' selected';
                                                } ?>>
                                                <?php echo $checkpoint['name']; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="form-text">
                                        Multiple Checkpoints (Models) can be selected, rotating with each new image
                                        generation.
                                        If none are chosen, the currently selected Checkpoint is applied.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="sampler"
                                           class="form-label">
                                        Samplers
                                    </label>
                                    <select name="sampler[]"
                                            id="sampler"
                                            multiple>
                                        <?php
                                        foreach ($params['samplers'] as $sampler) {
                                            ?>
                                            <option value="<?php echo $sampler['name']; ?>"
                                                <?php if ($sampler['selected']) {
                                                    echo ' selected';
                                                } ?>>
                                                <?php echo $sampler['name']; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="form-text">
                                        Multiple Samplers can be selected, rotating with each new image generation. If
                                        none are
                                        chosen, the default Sampler (Euler) is applied. Please note that not all
                                        Checkpoints
                                        (Models) can handle all Samplers.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="prompt"
                                           class="form-label">
                                        Prompt Merger Directory
                                    </label>
                                    <select class="form-select"
                                            id="prompt"
                                            name="prompt">
                                        <option value="">
                                            None
                                        </option>
                                        <?php
                                        foreach ($params['prompts'] as $prompt) {
                                            ?>
                                            <option value="<?php echo $prompt['name']; ?>"
                                                <?php if ($params['config']['prompt'] === $prompt['name']) {
                                                    echo 'selected';
                                                } ?>>
                                                <?php echo str_replace('_', ' ', $prompt['name']); ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="form-text">
                                        If no Prompt Merger Directories are selected, then the images will be generated
                                        with empty prompt
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="negativePrompt"
                                           class="form-label">
                                        Negative Prompt Merger Directory
                                    </label>
                                    <select class="form-select"
                                            id="negativePrompt"
                                            name="negativePrompt">
                                        <option value="">
                                            None
                                        </option>
                                        <?php
                                        foreach ($params['negative_prompts'] as $negativePrompt) {
                                            ?>
                                            <option value="<?php echo $negativePrompt['name']; ?>"
                                                <?php if ($params['config']['negativePrompt'] === $negativePrompt['name']) {
                                                    echo 'selected';
                                                } ?>>
                                                <?php echo str_replace('_', ' ', $negativePrompt['name']); ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="form-text">
                                        If no Negative Prompt Merger Directories are selected, then the images will be
                                        generated with empty negative prompt
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-4<?php if ($params['config']['mode'] !== 'img2img') {
                                    echo ' d-none';
                                } ?>"
                                     data-type="initImage">
                                    <label for="initImage"
                                           class="form-label">
                                        Initialize Images Directory (Only for img2img Mode)
                                    </label>
                                    <select class="form-select"
                                            id="initImage"
                                            name="initImage">
                                        <option value="">
                                            None
                                        </option>
                                        <?php
                                        foreach ($params['initImages'] as $initImage) {
                                            ?>
                                            <option value="<?php echo $initImage['name']; ?>"
                                                <?php if ($params['config']['initImages'] === $initImage['name']) {
                                                    echo 'selected';
                                                } ?>>
                                                <?php echo $initImage['name']; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="form-text">
                                        If no Initialize Images Directories are selected, then Initialize Images
                                        Directories are chosen randomly.
                                    </div>
                                </div>

                                <div class="col-12"></div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="width"
                                           class="form-label">
                                        Image width
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="width"
                                           name="width"
                                           min="10"
                                           max="8192"
                                           value="<?php echo $params['config']['width']; ?>"
                                           onchange="generator.widthOnchange()">
                                    <div class="form-text">
                                        In img2img Mode, all images in the Initialize Images Directories must share the
                                        same
                                        aspect ratio.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="height"
                                           class="form-label">
                                        Image height
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="height"
                                           name="height"
                                           min="10"
                                           max="8192"
                                           value="<?php echo $params['config']['height']; ?>"
                                           onchange="generator.heightOnchange()">
                                    <div class="form-text">
                                        In img2img Mode, all images in the Initialize Images Directories must share the
                                        same
                                        aspect ratio.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="steps"
                                           class="form-label">
                                        Number of sampling steps
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="steps"
                                           name="steps"
                                           min="1"
                                           max="100"
                                           value="<?php echo $params['config']['steps']; ?>"
                                           onchange="generator.stepsOnchange()">
                                </div>

                                <div class="col-12"></div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="restoreFaces"
                                           class="form-label">
                                        Use Restore Faces function
                                    </label>
                                    <select class="form-select"
                                            id="restoreFaces"
                                            name="restoreFaces">
                                        <option value="0"
                                            <?php if (!$params['config']['restoreFaces']) {
                                                echo 'selected';
                                            } ?>>
                                            false
                                        </option>
                                        <option value="1"
                                            <?php if ($params['config']['restoreFaces']) {
                                                echo 'selected';
                                            } ?>>
                                            true
                                        </option>
                                    </select>
                                    <div class="form-text">
                                        Option restore faces and remove artifacts.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="tiling"
                                           class="form-label">
                                        Use Tiling function
                                    </label>
                                    <select class="form-select"
                                            id="tiling"
                                            name="tiling">
                                        <option value="0"
                                            <?php if (!$params['config']['tiling']) {
                                                echo 'selected';
                                            } ?>>
                                            false
                                        </option>
                                        <option value="1"
                                            <?php if ($params['config']['tiling']) {
                                                echo 'selected';
                                            } ?>>
                                            true
                                        </option>
                                    </select>
                                    <div class="form-text">
                                        Tiling to generate seamless textures.
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-dark text-light">
                            <h4 class="mt-2">
                                Refiner
                            </h4>
                        </div>
                        <div class="card-body text-dark">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-4">
                                    <label for="refinerCheckpoint"
                                           class="form-label">
                                        Refiner Checkpoints (Models)
                                    </label>
                                    <select name="refinerCheckpoint[]"
                                            id="refinerCheckpoint"
                                            multiple>
                                        <?php
                                        foreach ($params['refiner_checkpoints'] as $refinerCheckpoint) {
                                            ?>
                                            <option value="<?php echo $refinerCheckpoint['name']; ?>"
                                                <?php if ($refinerCheckpoint['selected']) {
                                                    echo ' selected';
                                                } ?>>
                                                <?php echo $refinerCheckpoint['name']; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="form-text">
                                        Multiple Refiner Checkpoints (Models) can be selected, rotating with each new
                                        image
                                        generation. If none are chosen, the Refiner is disabled.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="refinerSwitchAt"
                                           class="form-label">
                                        Switch to Refiner Checkpoint at % of image creation.
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="refinerSwitchAt"
                                           name="refinerSwitchAt"
                                           min="1"
                                           max="100"
                                           value="<?php echo $params['config']['refinerSwitchAt']; ?>"
                                           onchange="generator.refinerSwitchAtOnchange()">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-dark text-light">
                            <h4 class="mt-2">
                                Lora
                            </h4>
                        </div>
                        <div class="card-body text-dark">
                            <div class="row">

                                <div class="col-12 col-md-6 mb-4">
                                    <label for="lora"
                                           class="form-label">
                                        Loras
                                    </label>
                                    <select name="lora[]"
                                            id="lora"
                                            multiple>
                                        <?php
                                        foreach ($params['loras'] as $lora) {
                                            ?>
                                            <option value="<?php echo $lora['name']; ?>"
                                                <?php if ($lora['selected']) {
                                                    echo ' selected';
                                                } ?>>
                                                <?php echo $lora['name']; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <small>
                                        If multiple Loras are selected, they will always be applied to all images.
                                    </small><br>
                                    <small class="text-warning">
                                        Not all Loras are compatible with all checkpoints.
                                    </small>
                                </div>

                                <div class="col-12 mb-4">
                                    <label for="lora"
                                           class="form-label">
                                        Lora Keywords
                                    </label>
                                    <div id="loraKeywordsWarning"
                                         class="alert alert-warning text-white">
                                        Select Loras to display keywords
                                    </div>
                                    <div id="loraKeywordsInput"></div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <?php
                    if ($params['upscalers']) {
                        ?>
                        <div class="card mb-4">
                            <div class="card-header bg-dark text-light">
                                <h5 class="mt-2">
                                    Height Resolution Fix
                                </h5>
                            </div>
                            <div class="card-body text-dark">
                                <div class="row">

                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="enableHr"
                                               class="form-label">
                                            Use Height Resolution Fix
                                        </label>
                                        <select class="form-select"
                                                id="enableHr"
                                                name="enableHr"
                                                onchange="generator.enableHrOnchange()">
                                            <option value="0"
                                                <?php if (!$params['config']['enableHr']) {
                                                    echo 'selected';
                                                } ?>>
                                                false
                                            </option>
                                            <option value="1"
                                                <?php if ($params['config']['enableHr']) {
                                                    echo 'selected';
                                                } ?>>
                                                true
                                            </option>
                                        </select>
                                        <div class="form-text">
                                            Creates a txt2txt -> img2img or img2img -> img2img loop, depending on the
                                            mode
                                            option.
                                            Creates the first image with the configured mode and all subsequent images
                                            as
                                            img2img
                                            with the generated image.
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 mb-4<?php if (!$params['config']['enableHr']) {
                                        echo ' d-none';
                                    } ?>"
                                         data-type="enableHr">
                                        <label for="hrUpscaler"
                                               class="form-label">
                                            Height Resolution Fix Upscaler
                                        </label>
                                        <select class="form-select"
                                                id="hrUpscaler"
                                                name="hrUpscaler">
                                            <?php
                                            foreach ($params['upscalers'] as $upscaler) {
                                                ?>
                                                <option value="<?php echo $upscaler; ?>"
                                                    <?php if ($params['config']['hrUpscaler'] === $upscaler) {
                                                        echo 'selected';
                                                    } ?>>
                                                    <?php echo $upscaler; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="form-text">
                                            If no Upscaler is selected, the default Upscaler (Latent) is used.
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 mb-4<?php if (!$params['config']['enableHr']) {
                                        echo ' d-none';
                                    } ?>"
                                         data-type="enableHr">
                                        <label for="hrResizeX"
                                               class="form-label">
                                            Resize Width
                                        </label>
                                        <input type="number"
                                               class="form-control"
                                               id="hrResizeX"
                                               name="hrResizeX"
                                               min="0"
                                               max="8192"
                                               value="<?php echo $params['config']['hrResizeX']; ?>"
                                               onchange="generator.hrSizeOnchange()">
                                        <div class="form-text">
                                            Width of the resized image. Only available if hires fix is enabled and hr
                                            scale is
                                            not configured or 0.
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 mb-4<?php if (!$params['config']['enableHr']) {
                                        echo ' d-none';
                                    } ?>"
                                         data-type="enableHr">
                                        <label for="hrResizeY"
                                               class="form-label">
                                            Resize Height
                                        </label>
                                        <input type="number"
                                               class="form-control"
                                               id="hrResizeY"
                                               name="hrResizeY"
                                               min="0"
                                               max="8192"
                                               value="<?php echo $params['config']['hrResizeY']; ?>"
                                               onchange="generator.hrSizeOnchange()">
                                        <div class="form-text">
                                            Height of the resized image. Only available if hires fix is enabled and hr
                                            scale is
                                            not configured or 0.
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 mb-4<?php if (!$params['config']['enableHr']) {
                                        echo ' d-none';
                                    } ?>"
                                         data-type="enableHr">
                                        <label for="hrScale"
                                               class="form-label">
                                            Scale of the resized image (Overrides Resize Width and Resize Height)
                                        </label>
                                        <input type="number"
                                               class="form-control"
                                               id="hrScale"
                                               name="hrScale"
                                               min="0"
                                               max="4"
                                               value="<?php echo $params['config']['hrScale']; ?>"
                                               onchange="generator.hrScaleOnchange()">
                                        <div class="form-text">
                                            Scale of the resized image. Only available if hires fix is enabled.
                                            Overrides Resize
                                            Width and Resize Height if configured and not 0.
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 mb-4<?php if (!$params['config']['enableHr']) {
                                        echo ' d-none';
                                    } ?>"
                                         data-type="enableHr">
                                        <label for="hrSamplerName"
                                               class="form-label">
                                            Height Resolution Fix Sampler
                                        </label>
                                        <select class="form-select"
                                                id="hrSamplerNamer"
                                                name="hrSamplerName">
                                            <option value="">
                                                None
                                            </option>
                                            <?php
                                            foreach ($params['samplers'] as $sampler) {
                                                ?>
                                                <option value="<?php echo $sampler['name']; ?>"
                                                    <?php if ($params['config']['hrSamplerName'] === $sampler['name']) {
                                                        echo 'selected';
                                                    } ?>>
                                                    <?php echo $sampler['name']; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="form-text">
                                            If none are chosen, the default Sampler (Euler) is applied. Please note that
                                            not all
                                            Checkpoints (Models) can handle all Samplers.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="mb-5">

                        <button class="float-start btn btn-primary"
                                type="button"
                                onclick="generator.save()">
                            <i class="bi bi-floppy-fill me-1"></i>
                            Save
                        </button>

                        <button class="float-end btn btn-primary"
                                type="button"
                                onclick="document.getElementById('form').submit()">
                            <i class="bi bi-lightning-fill me-1"></i>
                            Generate
                        </button>

                        <div class="clearfix"></div>

                    </div>

                </form>
            </div>
        </div>
    </div>
    <?php
}
?>

<script>
    class Generator {
        constructor() {
            this.loraKeywords = [];
            this.lorasSelected = null;
            this.checkpointSelector = new MultiSelectTag('checkpoint', {
                maxSelection: 100,
                required: false,
                placeholder: 'Select Checkpoints (Models)',
                onChange: function (selected) {
                    console.log('Selection changed:', selected);
                }
            });
            this.samplerSelector = new MultiSelectTag('sampler', {
                maxSelection: 100,
                required: false,
                placeholder: 'Select Samplers',
                onChange: function (selected) {
                    console.log('Selection changed:', selected);
                }
            });
            this.refinerCheckpointSelector = new MultiSelectTag('refinerCheckpoint', {
                maxSelection: 100,
                required: false,
                placeholder: 'Select Refiner Checkpoints (Models)',
                onChange: function (selected) {
                    console.log('Selection changed:', selected);
                }
            });
            this.loraSelector = new MultiSelectTag('lora', {
                maxSelection: 100,
                required: false,
                placeholder: 'Select Loras',
                onChange: function (selected) {
                    console.log('Selection changed:', selected);
                    generator.refreshLoraKeywords();
                }
            });
        }

        refreshLoraKeywords = () => {
            let select = document.getElementById("lora");
            let selectedLoras = [];
            let options = select && select.options;
            let opt;
            for (let i = 0, iLen = options.length; i < iLen; i++) {
                opt = options[i];
                if (opt.selected) {
                    selectedLoras.push(opt.value || opt.text);
                }
            }

            let index = 0;
            let html = '';
            if (this.loraKeywords.length > 0) {
                for (let ia = 0; ia < this.loraKeywords.length; ia++) {
                    if (!selectedLoras.includes(this.loraKeywords[ia]["alias"])) {
                        continue;
                    } else if (this.loraKeywords[ia].groups === "undefined") {
                        continue;
                    } else if (this.loraKeywords[ia].groups.length == 0) {
                        continue;
                    }
                    html += '<div><div><strong>Lora: </strong>' + this.loraKeywords[ia].alias + '</div>';
                    for (let ig = 0; ig < this.loraKeywords[ia].groups.length; ig++) {
                        if (this.loraKeywords[ia].groups[ig].keywords == "undefined") {
                            continue;
                        } else if (this.loraKeywords[ia].groups[ig].keywords.length == 0) {
                            continue;
                        }
                        html += '<div class="ms-2 mt-2"><div><strong>Group: </strong>' + this.loraKeywords[ia].groups[ig].name + '</div><div class="ms-2 pt-2">';
                        for (let ik = 0; ik < this.loraKeywords[ia].groups[ig].keywords.length; ik++) {
                            html += '<div class="form-check float-start me-3 mb-3">' +
                                '<input type="checkbox" class="form-check-input" name="loraKeywords[' + ia + '][' + ig + '][' + ik + ']" id="check' + index + '"';
                            if (this.loraKeywords[ia].groups[ig].keywords[ik].selected) {
                                html += ' checked';
                            }
                            html += ' onchange="generator.loraKeywordOnchange(' + ia + ', ' + ig + ', ' + ik + ')">' +
                                '<label class="form-check-label" for="check' + index + '">' + this.loraKeywords[ia].groups[ig].keywords[ik].name + ' (' + this.loraKeywords[ia].groups[ig].keywords[ik].training_units + ')</label>' +
                                '</div>';
                            index++;
                        }
                        html += '<div class="clearfix"></div></div></div>';
                    }
                    html += '</div>';
                }
            }
            if (index > 0) {
                document.getElementById("loraKeywordsInput").innerHTML = html;
                document.getElementById("loraKeywordsInput").classList.remove("d-none");
                document.getElementById("loraKeywordsWarning").classList.add("d-none");
            } else {
                document.getElementById("loraKeywordsInput").innerHTML = "";
                document.getElementById("loraKeywordsInput").classList.add("d-none");
                document.getElementById("loraKeywordsWarning").classList.remove("d-none");
            }
        }

        loraKeywordOnchange(ia, ig, ik) {
            let qsString = '[name="loraKeywords[' + ia + '][' + ig + '][' + ik + ']"]';
            let checked = document.querySelector(qsString).checked;
            this.loraKeywords[ia].groups[ig].keywords[ik].selected = checked;
        }

        numberOfImagesOnchange = () => {
            if (document.getElementById("numberOfImages").value > 1000000) {
                document.getElementById("numberOfImages").value = 1000000;
            } else if (document.getElementById("numberOfImages").value == "") {
                document.getElementById("numberOfImages").value = 0;
            }
        }

        modeOnchange = () => {
            if (document.getElementById("mode").value === "img2img") {
                document.querySelector('[data-type="initImage"]').classList.remove("d-none");
            } else {
                document.querySelector('[data-type="initImage"]').classList.add("d-none");
            }
        }

        widthOnchange = () => {
            if (document.getElementById("width").value > 8192) {
                document.getElementById("width").value = 8192;
            } else if (document.getElementById("width").value < 10) {
                document.getElementById("width").value = 10;
            } else if (document.getElementById("width").value == "") {
                document.getElementById("width").value = 512;
            }
        }

        heightOnchange = () => {
            if (document.getElementById("height").value > 8192) {
                document.getElementById("height").value = 8192;
            } else if (document.getElementById("height").value < 10) {
                document.getElementById("height").value = 10;
            } else if (document.getElementById("height").value == "") {
                document.getElementById("height").value = 512;
            }
        }

        stepsOnchange = () => {
            if (document.getElementById("steps").value > 100) {
                document.getElementById("steps").value = 100;
            } else if (document.getElementById("steps").value < 1) {
                document.getElementById("steps").value = 1;
            } else if (document.getElementById("steps").value == "") {
                document.getElementById("steps").value = 20;
            }
        }

        refinerSwitchAtOnchange = () => {
            if (document.getElementById("refinerSwitchAt").value > 100) {
                document.getElementById("refinerSwitchAt").value = 100;
            } else if (document.getElementById("refinerSwitchAt").value < 1) {
                document.getElementById("refinerSwitchAt").value = 1;
            } else if (document.getElementById("refinerSwitchAt").value == "") {
                document.getElementById("refinerSwitchAt").value = 70;
            }
        }

        enableHrOnchange = () => {
            if (document.getElementById("enableHr").value === "1") {
                document.querySelectorAll('[data-type="enableHr"]').forEach(function (element) {
                    element.classList.remove("d-none");
                });
            } else {
                document.querySelectorAll('[data-type="enableHr"]').forEach(function (element) {
                    element.classList.add("d-none");
                });
            }
        }

        hrSizeOnchange = () => {
            if (document.getElementById("hrResizeY").value > 8192) {
                document.getElementById("hrResizeY").value = 8192;
            } else if (document.getElementById("hrResizeY").value < 512) {
                document.getElementById("hrResizeY").value = 0;
            } else if (document.getElementById("hrResizeY").value == "") {
                document.getElementById("hrResizeY").value = 0;
            }
            if (document.getElementById("hrResizeX").value > 8192) {
                document.getElementById("hrResizeX").value = 8192;
            } else if (document.getElementById("hrResizeX").value < 512) {
                document.getElementById("hrResizeX").value = 0;
            } else if (document.getElementById("hrResizeX").value == "") {
                document.getElementById("hrResizeX").value = 0;
            }

            if (document.getElementById("hrResizeY").value > 0 && document.getElementById("hrResizeX").value > 0) {
                document.getElementById("hrScale").value = 0;
            }
        }

        hrScaleOnchange = () => {
            if (document.getElementById("hrScale").value > 4) {
                document.getElementById("hrScale").value = 4;
            } else if (document.getElementById("hrScale").value < 0) {
                document.getElementById("hrScale").value = 0;
            } else if (document.getElementById("hrScale").value == "") {
                document.getElementById("hrScale").value = 0;
            }

            if (document.getElementById("hrScale").value > 0) {
                document.getElementById("hrResizeY").value = 0;
                document.getElementById("hrResizeX").value = 0;
            }
        }

        save = () => {
            document.getElementById("action").value = "save";
            document.getElementById("form").submit();
        }
    }

    const generator = new Generator();
    generator.loraKeywords = <?php echo $params['lora_keywords']; ?>;
    generator.refreshLoraKeywords();
    console.log(generator.loraKeywords);
</script>