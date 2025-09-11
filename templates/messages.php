<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if ((isset($params['error']) && $params['error']) || (isset($params['success']) && $params['success'])) {
    ?>
    <div class="container" style="max-width: 1600px">
        <div class="row">
            <div class="col-12">
                <?php
                if (isset($params['error'])) {
                    ?>
                    <div class="col-12">
                        <div class="alert alert-danger mb-5">
                            <?php echo $params['error']; ?>
                        </div>
                    </div>
                    <?php
                }
                if (isset($params['success'])) {
                    ?>
                    <div class="col-12">
                        <div class="alert alert-success mb-5">
                            <?php echo $params['success']; ?>
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