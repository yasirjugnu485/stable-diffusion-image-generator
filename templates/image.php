<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

?>
<div class="col-12 mb-4"
     id="image-<?php echo $index; ?>">
    <div class="card border border-dark">
        <div class="card-header bg-dark text-light">
            <h5 class="float-start mb-0 mt-1">
                <?php
                $split = explode('/', $image['file']);
                echo end($split);
                ?>
            </h5>
            <div class="float-end">
                <button class="btn btn-danger"
                        type="button"
                        onclick="imageDelete.deleteClick(
                        <?php echo $index; ?>,
                                '<?php echo $image['file']; ?>'
                                )">
                    <i class="bi bi-trash-fill"></i>
                </button>
                <button class="btn btn-primary ms-4"
                        type="button"
                        onclick="imageCopy.copy('<?php echo $image['file']; ?>')">
                    <i class="bi bi-copy"></i>
                </button>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <h4>
                        Created image
                    </h4>
                    <?php
                    if (file_exists(ROOT_DIR . $image['file'])) {
                        ?>
                        <img class="w-100 border rounded"
                             style="cursor: pointer;"
                             src="/image.php?image=<?php echo urlencode($image['file']); ?>"
                             onclick="openModal(); currentSlide(<?php echo $index; ?>)">
                        <?php
                    } else {
                        $index--;
                        ?>
                        <div class="text-center">
                            <img class="w-100 rounded"
                                 src="/out/img/image-not-found.png">
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                if (isset($image['data']['init_images']) && file_exists(ROOT_DIR . $image['data']['init_images'])) {
                    ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0 d-block<?php if (!isset($image['data']['init_images']) || !file_exists(ROOT_DIR . $image['data']['init_images'])) {
                        echo ' d-none';
                    } ?>">
                        <h4>
                            Initial image
                        </h4>
                        <img class="w-100 border rounded"
                             src="/image.php?image=<?php echo urlencode($image['data']['init_images']); ?>">
                    </div>
                    <?php
                }
                ?>
                <div class="col-12 col-lg-4">
                    <h4>
                        Data
                    </h4>
                    <strong>
                        File:
                    </strong>
                    <?php
                    echo $image['file'];
                    ?>
                    </p>
                    <?php
                    if (isset($image['mode'])) {
                        ?>
                        <p>
                            <strong>
                                Mode:
                            </strong>
                            <a href="/<?php echo $image['mode']; ?>">
                                <?php echo $image['mode']; ?>
                            </a>
                        </p>
                        <?php
                    }
                    if (isset($image['data']['override_settings']['sd_model_checkpoint'])) {
                        ?>
                        <p>
                            <strong>
                                Checkpoint (Model):
                            </strong>
                            <a href="/checkpoints/<?php echo $image['data']['override_settings']['sd_model_checkpoint']; ?>">
                                <?php echo $image['data']['override_settings']['sd_model_checkpoint']; ?>
                            </a>
                        </p>
                        <?php
                    }
                    if (isset($image['data']['prompt']) && $image['data']['prompt']) {
                        ?>
                        <p>
                            <strong>
                                Prompt:
                            </strong>
                            <?php
                            echo htmlentities($image['data']['prompt']);
                            ?>
                        </p>
                        <?php
                    }
                    if (isset($image['data']['negativePrompt']) && $image['data']['negativePrompt']) {
                        ?>
                        <p>
                            <strong>
                                Negative Prompt:
                            </strong>
                            <?php
                            echo htmlentities($image['data']['negativePrompt']);
                            ?>
                        </p>
                        <?php
                    }
                    if (isset($image['data']['steps'])) {
                        ?>
                        <p>
                            <strong>
                                Steps:
                            </strong>
                            <?php
                            echo $image['data']['steps'];
                            ?>
                        </p>
                        <?php
                    }
                    if (isset($image['data']['width']) && isset($image['data']['height'])) {
                    ?>
                    <p>
                        <strong>
                            Size:
                        </strong>
                        <?php
                        echo $image['data']['width'] . ' x ' . $image['data']['height'];
                        ?>
                        <?php
                        }
                        ?>
                    </p>
                    <?php
                    if (isset($image['data']['refiner_checkpoint'])) {
                        ?>
                        <p>
                            <strong>
                                Refiner Checkpoint (Model):
                            </strong>
                            <a href="/checkpoints/<?php echo $image['data']['refiner_checkpoint']; ?>">
                                <?php echo $image['data']['refiner_checkpoint']; ?>
                            </a>
                        </p>
                        <p>
                            <strong>
                                Refiner switch at:
                            </strong>
                            <?php echo $image['data']['refiner_switch_at']; ?>
                        </p>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>