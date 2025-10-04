<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (!function_exists('parse_creation_time')) {
    function parse_creation_time(float $creationTime): string
    {
        $parsed = '';
        $creationTime = round($creationTime);
        $hours = floor($creationTime / 3600);
        if ($hours) {
            if ($hours > 1) {
                $parsed .= $hours . ' Hours ';
            } else {
                $parsed .= $hours . ' Hour ';
            }
            $creationTime = $creationTime - ($hours * 3600);
        }
        $minutes = floor($creationTime / 60);
        if ($minutes) {
            if ($minutes > 1) {
                $parsed .= $minutes . ' Minutes ';
            } else {
                $parsed .= $minutes . ' Minute ';
            }
            $creationTime = $creationTime - ($minutes * 60);
        }
        if ($creationTime === 0 || $creationTime > 1) {
            $parsed .= $creationTime . ' Seconds';
        } else {
            $parsed .= $creationTime . ' Second';
        }
        return $parsed;
    }
}

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
                    <h5>
                        Created image
                    </h5>
                    <?php
                    if (file_exists(ROOT_DIR . $image['file'])) {
                        ?>
                        <a class="photoswipe-children"
                           href="/image.php?image=<?php echo urlencode($image['file']); ?>"
                           data-pswp-width="<?php echo $image['data']['width'] * 100; ?>"
                           data-pswp-height="<?php echo $image['data']['height'] * 100; ?>">
                            <img class="w-100 border rounded"
                                 src="/image.php?image=<?php echo urlencode($image['file']); ?>">
                        </a>
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
                        <h5>
                            Initial image
                        </h5>
                        <img class="w-100 border rounded"
                             src="/image.php?image=<?php echo urlencode($image['data']['init_images']); ?>">
                    </div>
                    <?php
                }
                ?>
                <div class="col-12 col-lg-4">
                    <h5>
                        Data
                    </h5>
                    <p>
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
                    if (isset($image['data']['creation_time'])) {
                        ?>
                        <p>
                            <strong>
                                Creation time:
                            </strong>
                            <?php
                            echo parse_creation_time($image['data']['creation_time']);
                            ?>
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
                    if (isset($image['data']['negative_prompt']) && $image['data']['negative_prompt']) {
                        ?>
                        <p>
                            <strong>
                                Negative Prompt:
                            </strong>
                            <?php
                            echo htmlentities($image['data']['negative_prompt']);
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