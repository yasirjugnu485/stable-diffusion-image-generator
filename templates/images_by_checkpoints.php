<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

include(ROOT_DIR . 'templates/copy.php');
include(ROOT_DIR . 'templates/delete_image.php');
include(ROOT_DIR . 'templates/messages.php');
include(ROOT_DIR . 'templates/lightbox.php');
include(ROOT_DIR . 'templates/used_checkpoints.php');

$index = 1;
foreach ($params['images_by_checkpoints'] as $checkpoint => $images) {
    $params['breadcrumbs'] = array_merge($params['base_breadcrumbs'], [
        [
            'title' => $checkpoint,
            'url' => '/checkpoints/' . $checkpoint,
            'active' => true
        ]
    ]);
    include(ROOT_DIR . 'templates/breadcrumbs.php');
    ?>

    <div class="container mb-5">
        <div class="row">
            <?php
            if (count($images) > 0) {
                foreach ($images as $image) {
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
    <?php
}
?>