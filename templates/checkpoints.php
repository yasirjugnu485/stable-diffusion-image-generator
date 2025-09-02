<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (count($checkpoints)) {
    ?>
    <div class="container mb-5" style="max-width: 1600px">
        <div class="row">
            <div class="col-12">
                <h3>
                    Used Checkpoints (Models)
                </h3>
                <?php
                foreach ($checkpoints as $checkpoint) {
                    ?>
                    <a href="/checkpoints/<?php echo $checkpoint; ?>" style="text-decoration: none">
                        <button class="btn btn-secondary mb-2">
                            <?php echo $checkpoint; ?>
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