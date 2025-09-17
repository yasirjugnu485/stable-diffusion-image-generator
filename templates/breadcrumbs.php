<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['breadcrumbs']) && count($params['breadcrumbs'])) {
    ?>
    <div class="container mb-4">
        <div class="row">
            <div class="col-12">
                <div class="bg-light border rounded p-2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <?php foreach ($params['breadcrumbs'] as $breadcrumb) { ?>
                                <li class="breadcrumb-item<?php if ($breadcrumb['active']) {
                                    echo ' active';
                                } ?>"
                                        <?php if ($breadcrumb['active']) { ?> aria-current="page" <?php } ?>>
                                    <a href="<?php echo $breadcrumb['url']; ?>">
                                        <?php echo $breadcrumb['title']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <?php
}