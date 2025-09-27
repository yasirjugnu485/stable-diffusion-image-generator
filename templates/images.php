<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['images'])) {
    include(ROOT_DIR . 'templates/images_photoswipe.php');

    ?>
    <div class="container mb-5">
        <div id="photoswipe-gallery"
             class="row pswp-gallery pswp-gallery--single-column">
            <?php
            if (count($params['images'])) {
                $index = 1;
                foreach ($params['images'] as $image) {
                    if ($params['view'] === 'thumbnails') {
                        include(ROOT_DIR . 'templates/image_thumbnails.php');
                    } else {
                        include(ROOT_DIR . 'templates/image_list.php');
                    }
                    $index++;
                }
            } else {
                ?>
                <div class="col-12">
                    <div class="alert alert-warning">
                        There are no images available
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}