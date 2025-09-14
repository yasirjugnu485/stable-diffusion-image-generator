<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['used_modes']) && count($params['used_modes'])) {
    ?>
    <div class="container mb-4">
        <div class="row">
            <div class="col-12">
                <h4>
                    Used Modes
                </h4>
                <?php
                foreach ($params['used_modes'] as $mode) {
                    ?>
                    <a class="text-decoration-none"
                       href="/<?php echo $mode; ?>">
                        <button class="btn btn-outline-secondary mb-2">
                            <?php echo $mode; ?>
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