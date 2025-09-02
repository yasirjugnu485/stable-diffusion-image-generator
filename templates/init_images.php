<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (count($params['init_images'])) {
    ?>
    <div class="container mb-5" style="max-width: 1600px">
        <div class="row">
            <div class="col-12">
                <h3>
                    Initialize Images
                </h3>
                <?php
                foreach ($params['init_images'] as $initImage) {
                    ?>
                    <a href="/initialize-images/<?php echo $initImage; ?>" style="text-decoration: none">
                        <button class="btn btn-secondary mb-2">
                            <?php echo $initImage; ?>
                        </button>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}