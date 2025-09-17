<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['images'])) {
    include(ROOT_DIR . 'templates/images_lightbox.php');

    ?>
    <div class="container mb-5">
        <div class="row">
            <div class="col-12">
                <?php
                if (count($params['images'])) {
                    $index = 1;
                    foreach ($params['images'] as $image) {
                        include(ROOT_DIR . 'templates/image.php');
                        $index++;
                    }
                } else {
                    ?>
                    <div class="col-12">
                        <div class="alert alert-warning">
                            There are no images yet
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}