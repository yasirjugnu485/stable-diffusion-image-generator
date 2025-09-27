<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['refiner_checkpoints']) && count($params['refiner_checkpoints'])) {
    ?>
    <div class="container mb-4">
        <div class="row">
            <div class="col-12">
                <h4>
                    Used Refiner Checkpoints (Models)
                </h4>
                <?php
                foreach ($params['refiner_checkpoints'] as $checkpoint) {
                    ?>
                    <a class="text-decoration-none"
                       href="/checkpoints/<?php echo $checkpoint; ?>">
                        <button class="btn btn-outline-secondary mb-2 btn-pill">
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