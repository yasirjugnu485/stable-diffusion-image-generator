<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['init_images_directories'])) {
    ?>
    <div class="container mb-4">
        <div class="row">
            <?php
            if (count($params['init_images_directories'])) {
                ?>
                <div class="col-12">
                    <h4>
                        Initialize Images Directories
                    </h4>
                    <?php
                    foreach ($params['init_images_directories'] as $directory) {
                        ?>
                        <a class="text-decoration-none"
                           href="/initialize-images/<?php echo $directory; ?>">
                            <button class="btn btn-outline-secondary mb-2">
                                <?php echo str_replace('_', ' ', $directory); ?>
                            </button>
                        </a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <div class="col-12">
                    <h4>
                        Initialize Images Directories
                    </h4>
                    <div class="alert alert-warning text-white">
                        There are no Initialize Images Directories available
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}