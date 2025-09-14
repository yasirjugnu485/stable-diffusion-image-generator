<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['type']) && isset($params['type_directories']) && count($params['type_directories'])) {
    ?>
    <div class="container mb-4">
        <div class="row">
            <div class="col-12">
                <h4>
                    Generated <?php echo $params['type']; ?> Images
                </h4>
                <?php
                foreach ($params['type_directories'] as $directory) {
                    ?>
                    <a class="text-decoration-none"
                       href="/<?php echo $params['type']; ?>/<?php echo $directory; ?>">
                        <button class="btn btn-outline-secondary mb-2">
                            <?php echo $directory; ?>
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