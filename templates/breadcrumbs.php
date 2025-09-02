<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['breadcrumbs'])) {
    if (is_array($params['breadcrumbs'])) {
        if (count($params['breadcrumbs'])) {
            ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <?php foreach ($params['breadcrumbs'] as $breadcrumb) { ?>
                        <li class="breadcrumb-item<?php if ($breadcrumb['active']) {
                            echo ' active';
                        } ?>">
                            <a href="<?php echo $breadcrumb['url']; ?>">
                                <?php echo $breadcrumb['title']; ?>
                            </a>
                        </li>
                    <?php } ?>
                </ol>
            </nav>
            <?php
        }
    }
}